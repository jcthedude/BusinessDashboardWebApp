<?php

include_once("modules/config.php");

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['facebook_access_token'])):
        $access_token = $query['facebook_access_token'];
    else:
        callFacebookAuth();
    endif;

    if(isset($query['facebook_user'])):
        foreach ($query['facebook_user'] as $obj_user):
            //Run fql query
            $fql_query_user = json_decode(getFacebookUserDetails($access_token, $obj_user['facebook_uid']), true);

            //Check for errors
            if ($fql_query_user['error']):
                if ($fql_query_user['error']['type'] == "OAuthException"):
                    callFacebookAuth();
                else:
                    echo "Other Facebook authentication error has happened";
                endif;
            endif;

            echo '<pre>';
            echo 'User Details';
            echo '<br>';
            print_r($fql_query_user);
            echo '</pre>';
            echo '<br><br>';
        endforeach;
    endif;

    if(isset($query['facebook_page'])):
        foreach ($query['facebook_page'] as $obj_page):
            //Run fql query
            $fql_query_page = json_decode(getFacebookPageDetails($access_token, $obj_page['facebook_page_id']), true);

            //Check for errors
            if ($fql_query_page['error']):
                if ($fql_query_page['error']['type'] == "OAuthException"):
                    callFacebookAuth();
                else:
                    echo "Other Facebook authentication error has happened";
                endif;
            endif;

            echo '<pre>';
            echo 'Page Details:';
            echo '<br>';
            print_r($fql_query_page);
            echo '</pre>';
            echo '<br><br>';
        endforeach;
    endif;

    if(isset($_POST["user"])):
        // First check that required fields have been filled in.
        if (empty($_POST['post_user'])):
            $errors['post_user'] = "Post cannot be empty.";
        endif;
    endif;

    if(isset($_POST["user"]) && empty($errors)):
        foreach ($query['facebook_user'] as $obj_user):
            $fql_query_post = postFacebookUser($_POST['post_user'], $access_token, $obj_user['facebook_uid']);

            if (isset($fql_query_post['id'])):
                echo 'Post was published.';
            else:
                echo 'Post was not published.';
                print_r($fql_query_post);
            endif;
        endforeach;
    endif;

    if(isset($_POST["page"])):
        // First check that required fields have been filled in.
        if (empty($_POST['post_page'])):
            $errors['post_page'] = "Post cannot be empty.";
        endif;
    endif;

    if(isset($_POST["page"]) && empty($errors)):
        foreach ($query['facebook_page'] as $obj_user):
            $fql_query_post = postFacebookPage($_POST['post_page'], $access_token, $obj_user['facebook_page_id']);

            if (isset($fql_query_post['id'])):
                echo 'Post was published.';
            else:
                echo 'Post was not published.';
                print_r($fql_query_post);
            endif;
        endforeach;
    endif;

endif;

?>

<html>
<head>
    <title>Get Facebook Data and Post</title>
</head>
<body>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Post to User wall:
            </td>
            <td>
                <input type="text" name="post_user" value="">
                    <span class="error">
                        <?php echo isset($errors['post_user']) ? $errors['post_user'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <input name="user" type="submit" value="Post">
            </td>
        </tr>
    </table>
</form>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Post to Page wall:
            </td>
            <td>
                <input type="text" name="post_page" value="">
                    <span class="error">
                        <?php echo isset($errors['post_page']) ? $errors['post_page'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <input name="page" type="submit" value="Post">
            </td>
        </tr>
    </table>
</form>
</body>
</html>