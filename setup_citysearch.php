<?php

include_once('modules/config.php');

$dropdown_delete_citysearch = NULL;
$dropdown_add_citysearch = NULL;

$query = $coll->findOne(array('username' => $_SESSION["username"]));

if(isset($query['citysearch_business']['citysearch_id'])):
    $dropdown_delete_citysearch .= "<option value='" . $query['citysearch_business']['citysearch_id'] . "'>" . $query['citysearch_business']['citysearch_name'] . "</option>";
endif;

if(isset($_POST["search_citysearch"])):
    // First check that required fields have been filled in.
    if (empty($_POST['location_citysearch'])):
        $errors_citysearch['location_citysearch'] = "Location cannot be empty.";
    endif;
    if (empty($_POST['business_citysearch'])):
        $errors_citysearch['business_citysearch'] = "Business cannot be empty.";
    endif;
endif;

if (isset($_POST["search_citysearch"]) && empty($errors_citysearch)):
    $location_citysearch = str_replace(" ", "+", $_POST["location_citysearch"]);
    $business_citysearch = str_replace(" ", "+", $_POST["business_citysearch"]);

    $result_citysearch = makeCitysearchAPIRequestSearch($location_citysearch, $business_citysearch);

    foreach ($result_citysearch['results']['locations']as $obj_add_citysearch):
        $dropdown_add_citysearch .= "<option value='" . $obj_add_citysearch['id'] . "*" . $obj_add_citysearch['name'] . "'>" . $obj_add_citysearch['name'] . "--" . $obj_add_citysearch['address']['street'] . " " . $obj_add_citysearch['address']['city'] . ", ". $obj_add_citysearch['address']['state'] . "</option>";
    endforeach;
endif;

if(isset($_POST['submit_add_citysearch'])):
    $try = explode('*',$_POST['business_add_citysearch']);
    $citysearch_id = $try[0];
    $citysearch_name = $try[1];
    $try2 = explode('--',$citysearch_name);
    $citysearch_name = $try2[0];

    setCitysearchBusiness($query['username'], $citysearch_id, $citysearch_name);

    $dropdown_add_citysearch = NULL;

    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['citysearch_business']['citysearch_id'])):
        $dropdown_delete_citysearch .= "<option value='" . $query['citysearch_business']['citysearch_id'] . "'>" . $query['citysearch_business']['citysearch_name'] . "</option>";
    endif;
endif;

if(isset($_POST['submit_delete_citysearch'])):
    $citysearch_id = $_POST['business_delete_citysearch'];

    deleteCitysearchBusiness($query['username'], $citysearch_id);

    $dropdown_delete_citysearch = NULL;
endif;

?>

<div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
    <div class="box">

        <div class="box-header">
            <h2><i class="fa fa-star-half-o"></i><span class="break"></span>Citysearch</h2>
        </div>

        <div class="box-content">
            <table class="table">
                <tr>
                    <td>
                        <h1>Delete Current Business</h1>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <div class="controls">
                                    <select name="business_delete_citysearch" class="form-control">
                                        <?php print isset($dropdown_delete_citysearch) ? $dropdown_delete_citysearch : "" ; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit_delete_citysearch" class="btn btn-primary">Delete</button>
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
                                    <input class="form-control" name="location_citysearch" type="text" value="<?php print isset($_POST["location_citysearch"]) ? $_POST["location_citysearch"] : "" ; ?>" maxlength="50">
                                    <span class="error">
                                        <?php echo isset($errors_citysearch['location_citysearch']) ? $errors_citysearch['location_citysearch'] : ''; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <h4>Business Name</h4>
                                <div class="controls">
                                    <input class="form-control" name="business_citysearch" type="text" value="<?php print isset($_POST["business_citysearch"]) ? $_POST["business_citysearch"] : "" ; ?>" maxlength="50">
                                    <span class="error">
                                        <?php echo isset($errors_citysearch['business_citysearch']) ? $errors_citysearch['business_citysearch'] : ''; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="search_citysearch" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php if(isset($dropdown_add_citysearch)) : ?>
                    <tr>
                        <td>
                            <h1>Search Results</h1>
                            <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <select name="business_add_citysearch" class="form-control">
                                            <?php print isset($dropdown_add_citysearch) ? $dropdown_add_citysearch : "" ; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="submit_add_citysearch" class="btn btn-primary">Add Business</button>
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