<?php

include_once('modules/config.php');

$dropdown_delete_analytics = NULL;
$dropdown_add_analytics = NULL;

$query = $coll->findOne(array('username' => $_SESSION["username"]));
$refresh_token = $query['ga_refresh_token'];

if(isset($query['ga_web_property']['ga_property_id'])):
    $dropdown_delete_analytics .= "<option value='" . $query['ga_web_property']['ga_property_id'] . "'>" . $query['ga_web_property']['ga_property_name'] . "</option>";
endif;

if (!isset($query['ga_refresh_token'])):
    echo '<script> window.location="' . $get_ga_code_url . '"; </script> ';
else:
    $access_token = getAccessToken($refresh_token);

    //Get web properties for account
    if(isset($access_token)):
        $result_properties = getWebProperties($access_token);

        foreach ($result_properties['items'] as $obj_add):
            $dropdown_add_analytics .= "<option value='" . $obj_add['id'] . "*" . $obj_add['name'] . "'>" . $obj_add['name'] . "</option>";
        endforeach;
    endif;
endif;

if(isset($_POST['submit_add_analytics'])):
    $try = explode('*',$_POST['business_add_analytics']);
    $ga_property_id = $try[0];
    $ga_property_name = $try[1];

    setWebProperty($query['username'], $ga_property_id, $ga_property_name);

    $dropdown_delete_analytics = NULL;
    $dropdown_add_analytics = NULL;

    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['ga_web_property']['ga_property_id'])):
        $dropdown_delete_analytics .= "<option value='" . $query['ga_web_property']['ga_property_id'] . "'>" . $query['ga_web_property']['ga_property_name'] . "</option>";
    endif;

    $access_token = getAccessToken($refresh_token);

    //Get web properties for account
    if(isset($access_token)):
        $result_properties = getWebProperties($access_token);

        foreach ($result_properties['items'] as $obj_add):
            $dropdown_add_analytics .= "<option value='" . $obj_add['id'] . "*" . $obj_add['name'] . "'>" . $obj_add['name'] . "</option>";
        endforeach;
    endif;
endif;

if(isset($_POST['submit_delete_analytics'])):
    $ga_property_id = $_POST['business_delete_analytics'];

    deleteWebProperty($query['username'], $ga_property_id);

    $dropdown_delete_analytics = NULL;
endif;

?>

<div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
    <div class="box">

        <div class="box-header">
            <h2><i class="fa fa-dashboard blue"></i><span class="break"></span>Google Analytics</h2>
        </div>

        <div class="box-content">
            <table class="table">
                <tr>
                    <td>
                        <h2>Delete Current Web Property</h2>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <div class="controls">
                                    <select name="business_delete_analytics" class="form-control">
                                        <?php print isset($dropdown_delete_analytics) ? $dropdown_delete_analytics : "" ; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit_delete_analytics" class="btn btn-primary">Delete</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2>Choose Web Property</h2>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <div class="controls">
                                    <select name="business_add_analytics" class="form-control">
                                        <?php print isset($dropdown_add_analytics) ? $dropdown_add_analytics : "" ; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit_add_analytics" class="btn btn-primary">Add Web Property</button>
                            </div>
                        </form>
                    </td>
                </tr>
            </table>

            <div class="clearfix"></div>

        </div><!--/box content-->
    </div><!--/box-->
</div><!--/col-->