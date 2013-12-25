<?php

include_once('modules/config.php');

$dropdown_delete_facebook = NULL;
$dropdown_add_facebook = NULL;
$access_token = NULL;

$query = $coll->findOne(array('username' => $_SESSION["username"]));
$refresh_token = $query['ga_refresh_token'];

if(isset($query['facebook_page']['facebook_page_id'])):
    $dropdown_delete_facebook .= "<option value='" . $query['facebook_page']['facebook_page_id'] . "'>" . $query['facebook_page']['facebook_name'] . "</option>";
endif;

if(isset($query['facebook_access_token'])):
    $access_token = $query['facebook_access_token'];
else:
    callFacebookAuth();
endif;

// run fql query
$fql_query_page = json_decode(getFacebookPages($access_token), true);

//Check for errors
if (isset($fql_query_page['error'])):
    if ($fql_query_page['error']['type'] == "OAuthException"):
        callFacebookAuth();
    else:
        echo "Other Facebook authentication error has happened";
    endif;
endif;

foreach ($fql_query_page['data'] as $page_add):
    $dropdown_add_facebook .= "<option value='" . $page_add['page_id'] . "*" . $page_add['name'] . "'>" . $page_add['name'] . "</option>";
endforeach;

if(isset($_POST['submit_add_facebook'])):
    $try = explode('*',$_POST['business_add_facebook']);
    $facebook_page_id = $try[0];
    $facebook_name = $try[1];

    setFacebookPage($query['username'], $facebook_page_id, $facebook_name);

    $dropdown_delete_facebook = NULL;
    $dropdown_add_facebook = NULL;

    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['facebook_page']['facebook_page_id'])):
        $dropdown_delete_facebook .= "<option value='" . $query['facebook_page']['facebook_page_id'] . "'>" . $query['facebook_page']['facebook_name'] . "</option>";
    endif;

    // run fql query
    $fql_query_page = json_decode(getFacebookPages($access_token), true);

    //Check for errors
    if (isset($fql_query_page['error'])):
        if ($fql_query_page['error']['type'] == "OAuthException"):
            callFacebookAuth();
        else:
            echo "Other Facebook authentication error has happened";
        endif;
    endif;

    foreach ($fql_query_page['data'] as $page_add):
        $dropdown_add_facebook .= "<option value='" . $page_add['page_id'] . "*" . $page_add['name'] . "'>" . $page_add['name'] . "</option>";
    endforeach;
endif;

if(isset($_POST['submit_delete_facebook'])):
    $facebook_page_id = $_POST['business_delete_facebook'];

    deleteFacebookPage($query['username'], $facebook_page_id);

    $dropdown_delete_facebook = NULL;
endif;

if(isset($_POST["reauthorize_facebook"])):
    callFacebookAuth();
endif;

?>

<div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
    <div class="box">

        <div class="box-header">
            <h2><i class="fa fa-facebook green"></i><span class="break"></span>Facebook</h2>
        </div>

        <div class="box-content">
            <table class="table">
                <tr>
                    <td>
                        <h2>Delete Current Page</h2>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <div class="controls">
                                    <select name="business_delete_facebook" class="form-control">
                                        <?php print isset($dropdown_delete_facebook) ? $dropdown_delete_facebook : "" ; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit_delete_facebook" class="btn btn-primary">Delete</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2>Choose Page</h2>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <div class="controls">
                                    <select name="business_add_facebook" class="form-control">
                                        <?php print isset($dropdown_add_facebook) ? $dropdown_add_facebook : "" ; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit_add_facebook" class="btn btn-primary">Add Page</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2>Re-authorize Facebook Account</h2>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <p>Click here to re-authorize your account if you don't see your page listed</p>
                            <div class="form-actions">
                                <button type="submit" name="submit_add_facebook" class="btn btn-primary">Re-authorize</button>
                            </div>
                        </form>
                    </td>
                </tr>
            </table>

            <div class="clearfix"></div>

        </div><!--/box content-->
    </div><!--/box-->
</div><!--/col-->