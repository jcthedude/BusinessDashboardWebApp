<?php

include_once('modules/config.php');
include_once("modules/func.ga.views.php");
include_once("modules/func.facebook.views.php");
include_once("modules/func.twitter.views.php");
include_once("modules/func.yelp.views.php");
include_once("modules/func.places.views.php");
include_once("modules/func.citysearch.views.php");

if(!loggedIn()):
    echo '<script> window.location="login.php"; </script> ';
else:
    $monthly_metrics = getMonthlyDashboardMetrics();
    if(isset($monthly_metrics['totalsForAllResults']['ga:pageviews'])):
        $page_views = $monthly_metrics['totalsForAllResults']['ga:pageviews'];
    endif;
    if(isset($monthly_metrics['totalsForAllResults']['ga:visitors'])):
        $unique_visitors = $monthly_metrics['totalsForAllResults']['ga:visitors'];
    endif;

    $facebook_fans = getFacebookFans();
    $twitter_followers = getTwitterFollowers();
    $yelp_review_score = getYelpReviewScore();
    $places_review_score = getPlacesReviewScore();
    $citysearch_review_score = getCitysearchReviewScore();
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

                <div class="box">
                    <div class="box-header">
                        <h2><i class="fa fa-dashboard"></i><span class="break"></span>Activity</h2>
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-3 col-sm-6 col-xs-6 col-xxs-12">
                        <div class="smallstat box">
                            <i class="fa fa-desktop blue"></i>
                            <span class="title">Page Views (last 30 days)</span>
                            <span class="value"><?php print isset($page_views) ? $page_views : "N/A" ; ?></span>
                            <a href="google-analytics.php" class="more">
                                <span>View More Details</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                    <!--/col-->

                    <div class="col-lg-3 col-sm-6 col-xs-6 col-xxs-12">
                        <div class="smallstat box">
                            <i class="fa fa-group red"></i>
                            <span class="title">Visitors (last 30 days)</span>
                            <span class="value"><?php print isset($unique_visitors) ? $unique_visitors : "N/A" ; ?></span>
                            <a href="google-analytics.php" class="more">
                                <span>View More Details</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                    <!--/col-->

                    <div class="col-lg-3 col-sm-6 col-xs-6 col-xxs-12">
                        <div class="smallstat box">
                            <i class="fa fa-facebook green"></i>
                            <span class="title">Facebook Fans</span>
                            <span class="value"><?php print isset($facebook_fans) ? $facebook_fans : "N/A" ; ?></span>
                            <a href="facebook.php" class="more">
                                <span>View More Details</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                    <!--/col-->

                    <div class="col-lg-3 col-sm-6 col-xs-6 col-xxs-12">
                        <div class="smallstat box">
                            <i class="fa fa-twitter grey"></i>
                            <span class="title">Twitter Followers</span>
                            <span class="value"><?php print isset($twitter_followers) ? $twitter_followers : "N/A" ; ?></span>
                            <a href="twitter.php" class="more">
                                <span>View More Details</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                    <!--/col-->

                </div><!--/row-->
            </div><!--/col-->
        </div><!--/row-->

        <br><br>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-header">
                        <h2><i class="fa fa-bar-chart-o"></i><span class="break"></span>Website Traffic</h2>
                        <ul class="nav nav-tabs" id="mainCharts">
                            <li id="chartYear"><a href="dashboard.php#year">Year</a></li>
                            <li id="chartMonth" class="active"><a href="dashboard.php#month">Month</a></li>
                        </ul>
                    </div>
                    <div class="box-content">
                        <div class="tab-content">

                            <div class="tab-pane" id="year">
                                <div id="chart-year" style="height:250px;width:100%;"></div>
                            </div>

                            <div class="tab-pane active" id="month">
                                <div id="chart-month" style="height:250px;width:100%;"></div>
                            </div>

                        </div>
                    </div>
                </div>

            </div><!--/col-->
        </div><!--/row-->

        <br><br>

        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <h2><i class="fa fa-star-half-o"></i><span class="break"></span>Reviews</h2>
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
                        <div class="smallstat box">
                            <i class="fa fa-star-half-o pink"></i>
                            <span class="title">Yelp Review Score</span>
                            <span class="value"><?php print isset($yelp_review_score) ? $yelp_review_score : "N/A" ; ?></span>
                            <a href="yelp.php" class="more">
                                <span>View More Details</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                    <!--/col-->

                    <div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
                        <div class="smallstat box">
                            <i class="fa fa-google-plus lightBlue"></i>
                            <span class="title">Google Places Review Score</span>
                            <span class="value"><?php print isset($places_review_score) ? $places_review_score : "N/A" ; ?></span>
                            <a href="google-places.php" class="more">
                                <span>View More Details</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                    <!--/col-->

                    <div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
                        <div class="smallstat box">
                            <i class="fa fa-globe darkGreen"></i>
                            <span class="title">Citysearch Review Score</span>
                            <span class="value"><?php print isset($citysearch_review_score) ? $citysearch_review_score : "N/A" ; ?></span>
                            <a href="citysearch.php" class="more">
                                <span>View More Details</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                    <!--/col-->

                </div><!--/row-->
            </div><!--/col-->
        </div><!--/row-->
    </div> <!-- end: Content -->
    <?php include_once 'footer.php'; ?>

    <!-- inline scripts related to this page -->
    <script src="assets/js/pages/index.js"></script>
</body>
</html>