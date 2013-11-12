<?php
include_once("modules/config.php");

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    foreach ($query['facebook_user'] as $user_delete):
        $dropdown_user_delete .= "<option value='" . $user_delete['facebook_uid'] . "'>" . $user_delete['facebook_name'] . "</option>";
    endforeach;

    foreach ($query['facebook_page'] as $page_delete):
        $dropdown_page_delete .= "<option value='" . $page_delete['facebook_page_id'] . "'>" . $page_delete['facebook_name'] . "</option>";
    endforeach;

    if(isset($query['facebook_access_token'])):
        $access_token = $query['facebook_access_token'];
    endif;

    $code = $_REQUEST["code"];

    // get user access_token
    if (isset($code)):
        $access_token = getFacebookAccessToken($code);
        setFacebookAccessToken($query['username'], $access_token);
    endif;

    // run fql query
    $fql_query_user = json_decode(getFacebookUser($access_token), true);
    $fql_query_page = json_decode(getFacebookPages($access_token), true);

    //Check for errors
    if ($fql_query_user->error):
        // check to see if this is an oAuth error:
        if ($fql_query_user->error->type== "OAuthException"):
            // Retrieving a valid access token.
            $dialog_url = "https://www.facebook.com/dialog/oauth?"
                . "scope=" . $facebook_scope
                . "&client_id=" . $facebook_app_id
                . "&redirect_uri=" . urlencode($facebook_auth_url);
            echo("<script> top.location.href='" . $dialog_url
                . "'</script>");
        else:
            echo "other error has happened";
        endif;
    endif;

    foreach ($fql_query_user['data'] as $user_add):
        $dropdown_user_add .= "<option value='" . $user_add['uid'] . "*" . $user_add['first_name'] . " " . $user_add['last_name'] . "'>" . $user_add['first_name'] . " " . $user_add['last_name'] . "</option>";
    endforeach;

    foreach ($fql_query_page['data'] as $page_add):
        $dropdown_page_add .= "<option value='" . $page_add['page_id'] . "*" . $page_add['name'] . "'>" . $page_add['name'] . "</option>";
    endforeach;

    if(isset($_POST['submit_user_add'])):
        $try = explode('*',$_POST['user_add']);
        $facebook_uid = $try[0];
        $facebook_name = $try[1];

        setFacebookUser($query['username'], $facebook_uid, $facebook_name);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;

    if(isset($_POST['submit_page_add'])):
        $try = explode('*',$_POST['page_add']);
        $facebook_page_id = $try[0];
        $facebook_name = $try[1];

        setFacebookPage($query['username'], $facebook_page_id, $facebook_name);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;

    if(isset($_POST['submit_user_delete'])):
        $facebook_uid = $_POST['user_delete'];

        deleteFacebookUser($query['username'], $facebook_uid);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;

    if(isset($_POST['submit_page_delete'])):
        $facebook_page_id = $_POST['page_delete'];

        deleteFacebookPage($query['username'], $facebook_page_id);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;
endif;

?>

<html>
<head>
    <title>Setup Facebook Users and Pages</title>
</head>
<body>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Select a User to track:
            </td>
        </tr>
        <tr>
            <td>
                <select name="user_add">
                    <?php echo $dropdown_user_add;?>
                </select>
            </td>
            <td>
                <input name="submit_user_add" type="submit" value="Add User">
                </select>
            </td>
        </tr>
    </table>
</form>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Delete a User:
            </td>
        </tr>
        <tr>
            <td>
                <select name="user_delete">
                    <?php echo $dropdown_user_delete;?>
                </select>
            </td>
            <td>
                <input name="submit_user_delete" type="submit" value="Delete User">
                </select>
            </td>
        </tr>
    </table>
</form>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Select a Page to track:
            </td>
        </tr>
        <tr>
            <td>
                <select name="page_add">
                    <?php echo $dropdown_page_add;?>
                </select>
            </td>
            <td>
                <input name="submit_page_add" type="submit" value="Add Page">
                </select>
            </td>
        </tr>
    </table>
</form>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Delete a Page:
            </td>
        </tr>
        <tr>
            <td>
                <select name="page_delete">
                    <?php echo $dropdown_page_delete;?>
                </select>
            </td>
            <td>
                <input name="submit_page_delete" type="submit" value="Delete Page">
                </select>
            </td>
        </tr>
    </table>
</form>
</body>
</html>