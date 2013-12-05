<?php

include_once("modules/func.ga.views.php");
include_once("modules/func.facebook.views.php");
include_once("modules/func.twitter.views.php");

if(!loggedIn()):
    echo '<script> window.location="login.php"; </script> ';
else:
    $monthly_metrics = getMonthlyDashboardMetrics();
    $page_views = $monthly_metrics['totalsForAllResults']['ga:pageviews'];
    $unique_visitors = $monthly_metrics['totalsForAllResults']['ga:visitors'];

    $facebook_fans = getFacebookFans();
    $twitter_followers = getTwitterFollowers();
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
            <div class="row">

                <div class="col-lg-3 col-sm-6 col-xs-6 col-xxs-12">
                    <div class="smallstat box">
                        <i class="fa fa-desktop blue"></i>
                        <span class="title">Website Page Views</span>
                        <span class="value"><?php print isset($page_views) ? $page_views : "N/A" ; ?></span>
                        <a href="google-analytics.php" class="more">
                            <span>View More</span>
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
                            <span>View More</span>
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
                            <span>View More</span>
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
                            <span>View More</span>
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                <!--/col-->

            </div><!--/row-->
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
    </div> <!-- end: Content -->
    <?php include_once 'footer.php'; ?>
</body>
</html>