<?php

include_once("modules/func.ga.views.php");
include_once("modules/func.facebook.views.php");
include_once("modules/func.twitter.views.php");

if(!loggedIn()):
    echo '<script> window.location="login.php"; </script> ';
else:
    $page_views_visitors = getPageViewsVisitors();
    $page_views = $page_views_visitors['ga:pageviews'];
    $unique_visitors = $page_views_visitors['ga:visitors'];

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
    </div> <!-- end: Content -->
    <?php include_once 'footer.php'; ?>
</body>
</html>