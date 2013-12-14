<?php

include_once("modules/func.ga.views.php");
include_once("modules/func.facebook.views.php");
include_once("modules/func.twitter.views.php");
include_once("modules/func.yelp.views.php");
include_once("modules/func.places.views.php");
include_once("modules/func.citysearch.views.php");

if(!loggedIn()):
    echo '<script> window.location="login.php"; </script> ';
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
                                    <form class="form-horizontal col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="current_business">Business Name</label>
                                            <div class="controls">
                                                <input class="form-control disabled" id="current_business" name="current_business" type="text" placeholder="Current business name here" disabled="">
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="delete" class="btn btn-primary">Delete</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h2>Add New Business</h2>
                                    <form class="form-horizontal col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="location">Location</label>
                                            <div class="controls">
                                                <input class="form-control" id="location" name="location" type="text" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="business">Business Name</label>
                                            <div class="controls">
                                                <input class="form-control" id="business" name="business" type="text" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="search" class="btn btn-primary">Search</button>
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