<?php

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
    $page_views = $monthly_metrics['totalsForAllResults']['ga:pageviews'];
    $unique_visitors = $monthly_metrics['totalsForAllResults']['ga:visitors'];

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
                <div class="row">

                    <div class="col-lg-3 col-sm-6 col-xs-6 col-xxs-12">
                        <div class="smallstat box">
                            <i class="fa fa-desktop blue"></i>
                            <span class="title">Website Page Views</span>
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
                            <span class="title">Website Unique Visitors</span>
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

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h2><i class="fa fa-bar-chart-o"></i>Traffic</h2>
                        <ul class="nav nav-tabs" id="mainCharts">
                            <li id="chart24h"><a href="index.html#24h">24h</a></li>
                            <li id="chartWeek"><a href="index.html#week">week</a></li>
                            <li id="chartMonth" class="active"><a href="index.html#month">month</a></li>
                        </ul>
                    </div>
                    <div class="box-content">
                        <div class="tab-content">

                            <div class="tab-pane" id="24h">
                                <div id="chart-24h" style="height:250px;width:100%;"></div>
                            </div>

                            <div class="tab-pane" id="week">
                                <div id="chart-week" style="height:250px;width:100%;"></div>
                            </div>

                            <div class="tab-pane active" id="month">
                                <div id="chart-month" style="height:250px;width:100%;"></div>
                            </div>

                        </div>
                    </div>
                </div>

            </div><!--/col-->
        </div><!--/row-->

        <div class="row">
            <div class="col-xs-12">
                <div class="row">

                    <div class="col-lg-4 col-sm-6 col-xs-6 col-xxs-12">
                        <div class="smallstat box">
                            <i class="fa fa-star-half-o lightorange"></i>
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
</body>
</html>