<?php

include_once('modules/config.php');
include_once('modules/class.twitter.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));
    $twitter_oauth_token = $query['twitter_oauth_token'];
    $twitter_oauth_token_secret = $query['twitter_oauth_token_secret'];

    if(isset($twitter_oauth_token) && isset($twitter_oauth_token_secret)):
        if(isset($query['twitter_user'])):
            foreach ($query['twitter_user'] as $obj_user):
                $connection = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $twitter_oauth_token, $twitter_oauth_token_secret);
                $content = $connection->get('users/show', array('screen_name' => $obj_user['screen_name']));
                echo "Name: " . $content['name'] . '<br/>';
                echo "Username: " . $content['screen_name'] . '<br/>';
                echo "Description: " . $content['description'] . '<br/>';
                echo "Location: " . $content['location'] . '<br/>';
                echo "Followers: " . $content['followers_count'] . '<br/>';
                echo "Following: " . $content['friends_count'] . '<br/>';
                echo "Most Recent Tweet: " . '<br/>';
                echo "Time: " . $content['status']['created_at'] . '<br/>';
                echo $content['status']['text'] . '<br/>';
                echo "Retweets: " . $content['status']['retweet_count'] . '<br/>';
                echo "<br><br>";
            endforeach;
        endif;
    else:
        header('Location: twitter-login.php');
        exit();
    endif;

    if(isset($_POST["submit"])):
        // First check that required fields have been filled in.
        if (empty($_POST['tweet'])):
            $errors['tweet'] = "Tweet cannot be empty.";
        endif;
    endif;

    if (isset($_POST["submit"]) && empty($errors)):
        $connection = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $twitter_oauth_token, $twitter_oauth_token_secret);
        $tweet = $connection->post('statuses/update', array('status' => $_POST["tweet"]));
        if (isset($tweet)):
            echo '<script>parent.window.location.reload(true);</script>';
        endif;
    endif;
endif;

?>

<html>
<head>
    <title>See Twitter data and tweet</title>
</head>
<body>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Tweet:
            </td>
            <td>
                <input type="text" name="tweet" value="" maxlength="140">
                    <span class="error">
                        <?php echo isset($errors['tweet']) ? $errors['tweet'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <input name="submit" type="submit" value="Tweet">
            </td>
        </tr>
    </table>
</form>
</body>
</html>