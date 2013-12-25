<?php

include_once('modules/config.php');

$dropdown_delete_yelp = NULL;
$dropdown_add_yelp = NULL;

$query = $coll->findOne(array('username' => $_SESSION["username"]));

if(isset($query['yelp_business']['yelp_id'])):
    $dropdown_delete_yelp .= "<option value='" . $query['yelp_business']['yelp_id'] . "'>" . $query['yelp_business']['yelp_name'] . "</option>";
endif;

if(isset($_POST["search_yelp"])):
    // First check that required fields have been filled in.
    if (empty($_POST['location_yelp'])):
        $errors_yelp['location_yelp'] = "Location cannot be empty.";
    endif;
    if (empty($_POST['business_yelp'])):
        $errors_yelp['business_yelp'] = "Business cannot be empty.";
    endif;
endif;

if (isset($_POST["search_yelp"]) && empty($errors_yelp)):
    $location_yelp = str_replace(" ", "+", $_POST["location_yelp"]);
    $business_yelp = str_replace(" ", "+", $_POST["business_yelp"]);

    $result_yelp = makeYelpAPIRequestSearch($location_yelp, $business_yelp);

    foreach ($result_yelp['businesses'] as $obj_add_yelp):
        $dropdown_add_yelp .= "<option value='" . $obj_add_yelp['id'] . "*" . $obj_add_yelp['name'] . "'>" . $obj_add_yelp['name'] . "--" . $obj_add_yelp['location']['address'][0] . " " . $obj_add_yelp['location']['city'] . ", ". $obj_add_yelp['location']['state_code'] . "</option>";
    endforeach;
endif;

if(isset($_POST['submit_add_yelp'])):
    $try = explode('*',$_POST['business_add_yelp']);
    $yelp_id = $try[0];
    $yelp_name = $try[1];
    $try2 = explode('--',$yelp_name);
    $yelp_name = $try2[0];

    setYelpBusiness($query['username'], $yelp_id, $yelp_name);

    $dropdown_delete_yelp = NULL;
    $dropdown_add_yelp = NULL;

    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['yelp_business']['yelp_id'])):
        $dropdown_delete_yelp .= "<option value='" . $query['yelp_business']['yelp_id'] . "'>" . $query['yelp_business']['yelp_name'] . "</option>";
    endif;
endif;

if(isset($_POST['submit_delete_yelp'])):
    $yelp_id = $_POST['business_delete_yelp'];

    deleteYelpBusiness($query['username'], $yelp_id);

    $dropdown_delete_yelp = NULL;
endif;

?>

<div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
    <div class="box">

        <div class="box-header">
            <h2><i class="fa fa-star-half-o pink"></i><span class="break"></span>Yelp</h2>
        </div>

        <div class="box-content">
            <table class="table">
                <tr>
                    <td>
                        <h2>Delete Current Business</h2>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <div class="controls">
                                    <select name="business_delete_yelp" class="form-control">
                                        <?php print isset($dropdown_delete_yelp) ? $dropdown_delete_yelp : "" ; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit_delete_yelp" class="btn btn-primary">Delete</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2>Add New Business</h2>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <p>Location</p>
                                <div class="controls">
                                    <input class="form-control" name="location_yelp" type="text" value="<?php print isset($_POST["location_yelp"]) ? $_POST["location_yelp"] : "" ; ?>" maxlength="50">
                                    <span class="error">
                                        <?php echo isset($errors_yelp['location_yelp']) ? $errors_yelp['location_yelp'] : ''; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <p>Business Name</p>
                                <div class="controls">
                                    <input class="form-control" name="business_yelp" type="text" value="<?php print isset($_POST["business_yelp"]) ? $_POST["business_yelp"] : "" ; ?>" maxlength="50">
                                    <span class="error">
                                        <?php echo isset($errors_yelp['business_yelp']) ? $errors_yelp['business_yelp'] : ''; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="search_yelp" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php if(isset($dropdown_add_yelp)) : ?>
                    <tr>
                        <td>
                            <h2>Search Results</h2>
                            <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <select name="business_add_yelp" class="form-control">
                                            <?php print isset($dropdown_add_yelp) ? $dropdown_add_yelp : "" ; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="submit_add_yelp" class="btn btn-primary">Add Business</button>
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