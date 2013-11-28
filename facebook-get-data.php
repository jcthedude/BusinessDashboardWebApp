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

    if(isset($query['facebook_page'])):
        foreach ($query['facebook_page'] as $obj_page):
            //Run fql and opengraph queries
            $fql_query_page = json_decode(getFacebookPageDetails($access_token, $obj_page['facebook_page_id']), true);
            $opengraph_query_page = json_decode(getFacebookPageFeed($access_token, $obj_page['facebook_page_id']), true);

            //Check for errors
            if ($fql_query_page['error']):
                if ($fql_query_page['error']['type'] == "OAuthException"):
                    callFacebookAuth();
                else:
                    echo "Other Facebook authentication error has happened";
                endif;
            endif;

            echo 'Page Details:';
            echo '<br>';
            echo '<pre>';
            print_r($fql_query_page);
            echo '</pre>';
            echo '<br><br>';

//            echo '<pre>';
//            echo 'Page Feed:';
//            echo '<br>';
//            print_r($opengraph_query_page);
//            echo '</pre>';
//            echo '<br><br>';

            echo 'Page Feed:';
            echo '<br>';
            foreach ($opengraph_query_page['data'] as $obj):
                echo '<pre>';
                echo $obj['from']['name'];
                echo '<br>';
                echo $obj['message'];
                echo '<br>';
                if (isset ($obj['likes']['data'])):
                    echo 'Likes: ' . count($obj['likes']['data']);
                else: echo 'Likes: 0';
                endif;
                echo '<br>';
                if (isset ($obj['comments']['data'])):
                    echo '     Comments';
                    echo '<br>';
                    foreach ($obj['comments']['data'] as $obj2):
                        echo '     ' . $obj2['from']['name'];
                        echo '<br>';
                        echo '     ' . $obj2['message'];
                        echo '<br>';
                        echo '     ' . 'Likes: '. $obj2['like_count'];
                        echo '<br>';
                    endforeach;
                endif;
                echo '</pre>';
                echo '<br><br>';
            endforeach;
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
                Post to Page Wall:
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