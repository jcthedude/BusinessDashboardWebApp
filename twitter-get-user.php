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

    foreach ($query['twitter_user'] as $obj_delete):
        $dropdown_delete .= "<option value='" . $obj_delete['screen_name'] . "'>" . $obj_delete['screen_name'] . "</option>";
    endforeach;

    if(isset($_POST["search"])):
        // First check that required fields have been filled in.
        if (empty($_POST['username'])):
            $errors['username'] = "Username cannot be empty.";
        endif;
    endif;

    if (isset($_POST["search"]) && empty($errors)):
        if(isset($twitter_oauth_token) && isset($twitter_oauth_token_secret)):
            $connection = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $twitter_oauth_token, $twitter_oauth_token_secret);
            $result_twitter = $connection->get('users/search', array('q' => $_POST["username"]));

            foreach ($result_twitter as $obj_add):
                $dropdown_add .= "<option value='" . $obj_add['screen_name'] . "'>" . $obj_add['screen_name'] . "</option>";
            endforeach;
        else:
            header('Location: twitter-login.php');
            exit();
        endif;
    endif;

    if(isset($_POST['submit_add'])):
        getTwitterUser($query['username'], $_POST['user_add']);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;

    if(isset($_POST['submit_delete'])):
        deleteTwitterUser($query['username'], $_POST['user_delete']);
        echo '<script>parent.window.location.reload(true);</script>';
    endif;
endif;

?>

<html>
<head>
    <title>Search Twitter for a Username</title>
</head>
<body>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Username:
            </td>
            <td>
                <input type="text" name="username" value="" maxlength="50">
                    <span class="error">
                        <?php echo isset($errors['username']) ? $errors['username'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <input name="search" type="submit" value="Search">
            </td>
        </tr>
    </table>
</form>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Select a Username:
            </td>
        </tr>
        <tr>
            <td>
                <select name="user_add">
                    <?php echo $dropdown_add;?>
                </select>
            </td>
            <td>
                <input name="submit_add" type="submit" value="Add Username">
                </select>
            </td>
        </tr>
    </table>
</form>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Delete a Username:
            </td>
        </tr>
        <tr>
            <td>
                <select name="user_delete">
                    <?php echo $dropdown_delete;?>
                </select>
            </td>
            <td>
                <input name="submit_delete" type="submit" value="Delete Username">
                </select>
            </td>
        </tr>
    </table>
</form>
</body>
</html>