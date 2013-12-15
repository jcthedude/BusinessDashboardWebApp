<?php

include_once('modules/config.php');
include_once('modules/class.oauth.php');
include_once("modules/func.user.php");

if(!loggedIn()):
    echo '<script> window.location="login.php"; </script> ';
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    foreach ($query['yelp_business'] as $obj_delete):
        $dropdown_delete .= "<option value='" . $obj_delete['yelp_id'] . "'>" . $obj_delete['yelp_name'] . "</option>";
    endforeach;

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
endif;

?>

<?php include_once 'header.php'; ?>
</head>
<body>
<?php include_once 'navbar.php'; ?>
<?php include_once 'menubar.php'; ?>

<!-- start: Content -->
<div id="content" class="col-lg-10 col-sm-11 ">
    <div class="row">
        <div class="col-xs-12">

            <div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
                <div class="box">

                    <div class="box-header">
                        <h2><i class="fa fa-star-half-o"></i><span class="break"></span>Yelp</h2>
                    </div>

                    <div class="box-content">
                        <table class="table">
                            <tr>
                                <td>
                                    <h2>Delete Current Business</h2>
                                    <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="business_delete">Business Name</label>
                                            <div class="controls">
                                                <select id="business_delete" name="business_delete" class="form-control">
                                                    <?php echo $dropdown_delete;?>
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
                                    <h2>Add New Business</h2>
                                    <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="location">Location</label>
                                            <div class="controls">
                                                <input class="form-control" id="location" name="location" type="text" value="<?php print isset($_POST["location"]) ? $_POST["location"] : "" ; ?>" maxlength="50">
                                                <span class="error">
                                                    <?php echo isset($errors['location']) ? $errors['location'] : ''; ?>
                                                </span><br />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="business">Business Name</label>
                                            <div class="controls">
                                                <input class="form-control" id="business" name="business" type="text" value="<?php print isset($_POST["business"]) ? $_POST["business"] : "" ; ?>" maxlength="50">
                                                <span class="error">
                                                    <?php echo isset($errors['business']) ? $errors['business'] : ''; ?>
                                                </span><br />
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="search" class="btn btn-primary">Search</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h2>Search Results</h2>
                                    <form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" class="form-horizontal col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="business_add">Results</label>
                                            <div class="controls">
                                                <select id="business_add" name="business_add" class="form-control">
                                                    <?php echo $dropdown_add;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="submit_add" class="btn btn-primary">Add Business</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </table>

                        <div class="clearfix"></div>

                    </div><!--/box content-->
                </div><!--/box-->
            </div><!--/col-->
        </div><!--/col-->
    </div><!--/row-->

    <br><br>

</div> <!-- end: Content -->
<?php include_once 'footer.php'; ?>

<!-- inline scripts related to this page -->

</body>
</html>