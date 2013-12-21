<?php

include_once('modules/config.php');

$dropdown_delete_twitter = NULL;
$dropdown_add_twitter = NULL;

$query = $coll->findOne(array('username' => $_SESSION["username"]));
$twitter_oauth_token = $query['twitter_oauth_token'];
$twitter_oauth_token_secret = $query['twitter_oauth_token_secret'];

if(isset($query['twitter_user']['screen_name'])):
    $dropdown_delete_twitter .= "<option value='" . $query['twitter_user']['screen_name'] . "'>" . $query['twitter_user']['screen_name'] . "</option>";
endif;

if(isset($_POST["search_twitter"])):
    // First check that required fields have been filled in.
    if (empty($_POST['username_twitter'])):
        $errors_twitter['username_twitter'] = "Username cannot be empty.";
    endif;
endif;

if (isset($_POST["search_twitter"]) && empty($errors_twitter)):
    if(isset($twitter_oauth_token) && isset($twitter_oauth_token_secret)):
        $connection = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $twitter_oauth_token, $twitter_oauth_token_secret);
        $result_twitter = $connection->get('users/search', array('q' => $_POST["username_twitter"]));

        foreach ($result_twitter as $obj_add):
            $dropdown_add_twitter .= "<option value='" . $obj_add['screen_name'] . "'>" . $obj_add['screen_name'] . "</option>";
        endforeach;
    else:
        echo '<script> window.location="twitter-login.php"; </script> ';
    endif;
endif;

if(isset($_POST['submit_add_twitter'])):
    setTwitterUser($query['username'], $_POST['business_add_twitter']);

    $dropdown_add_twitter = NULL;

    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['twitter_user']['screen_name'])):
        $dropdown_delete_twitter .= "<option value='" . $query['twitter_user']['screen_name'] . "'>" . $query['twitter_user']['screen_name'] . "</option>";
    endif;
endif;

if(isset($_POST['submit_delete_twitter'])):
    deleteTwitterUser($query['username'], $_POST['business_delete_twitter']);

    $dropdown_delete_twitter = NULL;
endif;

?>

<div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
    <div class="box">

        <div class="box-header">
            <h2><i class="fa fa-star-half-o"></i><span class="break"></span>Twitter</h2>
        </div>

        <div class="box-content">
            <table class="table">
                <tr>
                    <td>
                        <h1>Delete Current User</h1>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <div class="controls">
                                    <select name="business_delete_twitter" class="form-control">
                                        <?php print isset($dropdown_delete_twitter) ? $dropdown_delete_twitter : "" ; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit_delete_twitter" class="btn btn-primary">Delete</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h1>Add New Twitter User</h1>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <h4>Username</h4>
                                <div class="controls">
                                    <input class="form-control" name="username_twitter" type="text" value="<?php print isset($_POST["username_twitter"]) ? $_POST["username_twitter"] : "" ; ?>" maxlength="50">
                                    <span class="error">
                                        <?php echo isset($errors_twitter['username_twitter']) ? $errors_twitter['username_twitter'] : ''; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="search_twitter" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php if(isset($dropdown_add_twitter)) : ?>
                    <tr>
                        <td>
                            <h1>Search Results</h1>
                            <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <select name="business_add_twitter" class="form-control">
                                            <?php print isset($dropdown_add_twitter) ? $dropdown_add_twitter : "" ; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="submit_add_twitter" class="btn btn-primary">Add User</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>

            <div class="clearfix"></div>

        </div><!--/box content-->
    </div><!--/box-->
</div><!--/col-->