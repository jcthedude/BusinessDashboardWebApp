<?php

include_once('modules/config.php');

$dropdown_delete_places = NULL;
$dropdown_add_places = NULL;

$query = $coll->findOne(array('username' => $_SESSION["username"]));

if(isset($query['places_business']['places_id'])):
    $dropdown_delete_places .= "<option value='" . $query['places_business']['places_id'] . "'>" . $query['places_business']['places_name'] . "</option>";
endif;

if(isset($_POST["search_places"])):
    // First check that required fields have been filled in.
    if (empty($_POST['location_places'])):
        $errors_places['location_places'] = "Location cannot be empty.";
    endif;
    if (empty($_POST['business_places'])):
        $errors_places['business_places'] = "Business cannot be empty.";
    endif;
endif;

if (isset($_POST["search_places"]) && empty($errors_places)):
    $location_places = str_replace(" ", "+", $_POST["location_places"]);
    $business_places = str_replace(" ", "+", $_POST["business_places"]);

    $result_geocode = makeGeolocationAPIRequest($location_places);
    $result_places = makePlacesAPIRequestSearch($result_geocode, $business_places);

    foreach ($result_places['results'] as $obj_add_places):
        $dropdown_add_places .= "<option value='" . $obj_add_places['reference'] . "*" . $obj_add_places['name'] . "'>" . $obj_add_places['name'] . "--" . $obj_add_places['vicinity'] ."</option>";
    endforeach;
endif;

if(isset($_POST['submit_add_places'])):
    $try = explode('*',$_POST['business_add_places']);
    $places_id = $try[0];
    $places_name = $try[1];
    $try2 = explode('--',$places_name);
    $places_name = $try2[0];

    setPlacesBusiness($query['username'], $places_id, $places_name);
    echo '<script>parent.window.location.reload(true);</script>';
endif;

if(isset($_POST['submit_delete_places'])):
    $places_id = $_POST['business_delete_places'];

    deletePlacesBusiness($query['username'], $places_id);
    echo '<script>parent.window.location.reload(true);</script>';
endif;

?>

<div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
    <div class="box">

        <div class="box-header">
            <h2><i class="fa fa-star-half-o"></i><span class="break"></span>Places</h2>
        </div>

        <div class="box-content">
            <table class="table">
                <tr>
                    <td>
                        <h1>Delete Current Business</h1>
                        <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                            <div class="form-group">
                                <div class="controls">
                                    <select name="business_delete_places" class="form-control">
                                        <?php print isset($dropdown_delete_places) ? $dropdown_delete_places : "" ; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit_delete_places" class="btn btn-primary">Delete</button>
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
                                    <input class="form-control" name="location_places" type="text" value="<?php print isset($_POST["location_places"]) ? $_POST["location_places"] : "" ; ?>" maxlength="50">
                                    <span class="error">
                                        <?php echo isset($errors_places['location_places']) ? $errors_places['location_places'] : ''; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <h4>Business Name</h4>
                                <div class="controls">
                                    <input class="form-control" name="business_places" type="text" value="<?php print isset($_POST["business_places"]) ? $_POST["business_places"] : "" ; ?>" maxlength="50">
                                    <span class="error">
                                        <?php echo isset($errors_places['business_places']) ? $errors_places['business_places'] : ''; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="search_places" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php if(isset($dropdown_add_places)) : ?>
                    <tr>
                        <td>
                            <h1>Search Results</h1>
                            <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <select name="business_add_places" class="form-control">
                                            <?php print isset($dropdown_add_places) ? $dropdown_add_places : "" ; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="submit_add_places" class="btn btn-primary">Add Business</button>
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