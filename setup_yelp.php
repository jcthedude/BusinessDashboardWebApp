<?php

include_once('modules/config.php');

$dropdown_delete = NULL;
$dropdown_add = NULL;

$query = $coll->findOne(array('username' => $_SESSION["username"]));

if(isset($query['yelp_business']['yelp_id'])):
    $dropdown_delete .= "<option value='" . $query['yelp_business']['yelp_id'] . "'>" . $query['yelp_business']['yelp_name'] . "</option>";
endif;

if(isset($_POST["search"])):
    // First check that required fields have been filled in.
    if (empty($_POST['location'])):
        $errors['location'] = "Location cannot be empty.";
    endif;
    if (empty($_POST['business'])):
        $errors['business'] = "Business cannot be empty.";
    endif;
endif;

if (isset($_POST["search"]) && empty($errors)):
    $location = str_replace(" ", "+", $_POST["location"]);
    $business = str_replace(" ", "+", $_POST["business"]);

    $result_yelp = makeYelpAPIRequestSearch($location, $business);

    foreach ($result_yelp['businesses'] as $obj_add):
        $dropdown_add .= "<option value='" . $obj_add['id'] . "*" . $obj_add['name'] . "'>" . $obj_add['name'] . "</option>";
    endforeach;
endif;

if(isset($_POST['submit_add'])):
    $try = explode('*',$_POST['business_add']);
    $yelp_id = $try[0];
    $yelp_name = $try[1];

    setYelpBusiness($query['username'], $yelp_id, $yelp_name);
    echo '<script>parent.window.location.reload(true);</script>';
endif;

if(isset($_POST['submit_delete'])):
    $yelp_id = $_POST['business_delete'];

    deleteYelpBusiness($query['username'], $yelp_id);
    echo '<script>parent.window.location.reload(true);</script>';
endif;

?>

<div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
    <div class="box">

        <div class="box-header">
            <h2><i class="fa fa-star-half-o"></i><span class="break"></span>Yelp</h2>
        </div>

        <div class="box-content">
            <table class="table">
                <tr>
                    <td>
                        <h1>Delete Current Business</h1>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <div class="controls">
                                    <select name="business_delete" class="form-control">
                                        <?php print isset($dropdown_delete) ? $dropdown_delete : "" ; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit_delete" class="btn btn-primary">Delete</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h1>Add New Business</h1>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <h4>Location</h4>
                                <div class="controls">
                                    <input class="form-control" name="location" type="text" value="<?php print isset($_POST["location"]) ? $_POST["location"] : "" ; ?>" maxlength="50">
                                    <span class="error">
                                        <?php echo isset($errors['location']) ? $errors['location'] : ''; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <h4>Business Name</h4>
                                <div class="controls">
                                    <input class="form-control" name="business" type="text" value="<?php print isset($_POST["business"]) ? $_POST["business"] : "" ; ?>" maxlength="50">
                                    <span class="error">
                                        <?php echo isset($errors['business']) ? $errors['business'] : ''; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="search" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php if(isset($dropdown_add)) : ?>
                    <tr>
                        <td>
                            <h1>Search Results</h1>
                            <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <select name="business_add" class="form-control">
                                            <?php print isset($dropdown_add) ? $dropdown_add : "" ; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="submit_add" class="btn btn-primary">Add Business</button>
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