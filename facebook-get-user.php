<?php
include_once("modules/config.php");

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    foreach ($query['facebook_page'] as $page_delete):
        $dropdown_page_delete .= "<option value='" . $page_delete['facebook_page_id'] . "'>" . $page_delete['facebook_name'] . "</option>";
    endforeach;

    if(isset($query['facebook_access_token'])):
        $access_token = $query['facebook_access_token'];
    else:
        callFacebookAuth();
    endif;

    // run fql query
    $fql_query_page = json_decode(getFacebookPages($access_token), true);

    //Check for errors
    if ($fql_query_page['error']):
        if ($fql_query_page['error']['type'] == "OAuthException"):
            callFacebookAuth();
        else:
            echo "Other Facebook authentication error has happened";
        endif;
    endif;

    foreach ($fql_query_page['data'] as $page_add):
        $dropdown_page_add .= "<option value='" . $page_add['page_id'] . "*" . $page_add['name'] . "'>" . $page_add['name'] . "</option>";
    endforeach;

    if(isset($_POST['submit_page_add'])):
        $try = explode('*',$_POST['page_add']);
        $facebook_page_id = $try[0];
        $facebook_name = $try[1];

        setFacebookPage($query['username'], $facebook_page_id, $facebook_name);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;

    if(isset($_POST['submit_page_delete'])):
        $facebook_page_id = $_POST['page_delete'];

        deleteFacebookPage($query['username'], $facebook_page_id);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;

    if(isset($_POST["reauthorize"])):
        callFacebookAuth();
    endif;
endif;

?>

<html>
<head>
    <title>Setup Facebook Pages</title>
</head>
<body>
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
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <input name="reauthorize" type="submit" value="Reauthorize Business Dashboard App if your page isn't showing">
            </td>
        </tr>
    </table>
</form>
</body>
</html>