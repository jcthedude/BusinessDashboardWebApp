<?php

include_once("modules/config.php");
include_once("modules/class.facebook.php");

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['facebook_id'])):
        $_SESSION["fb_".$facebook_app_id."_access_token"] = $query['facebook_access_token'];

        $facebook = new Facebook(array(
            'appId'  => $facebook_app_id,
            'secret' => $facebook_app_secret,
        ));

        try {
            $user = $facebook->getUser();
            if($user):
                $user_profile = $facebook->api('/me');

                $facebook->setExtendedAccessToken();
                $access_token = $_SESSION["fb_".$facebook_app_id."_access_token"];
                $facebook->setAccessToken($access_token);
                $facebook_access_token = $facebook->getAccessToken();

                getFacebookAccessToken($query['username'], $user_profile['id'], $facebook_access_token);

                $facebook_id = $query['facebook_id'];
            else:
                $loginUrl = $facebook->getLoginUrl();
            endif;
        } catch (FacebookApiException $e) {
            $loginUrl = $facebook->getLoginUrl();
        }
    else:
        $facebook = new Facebook(array(
            'appId'  => $facebook_app_id,
            'secret' => $facebook_app_secret,
        ));

        $user = $facebook->getUser();

        if($user):
            $user_profile = $facebook->api('/me');

            $facebook->setExtendedAccessToken();
            $access_token = $_SESSION["fb_".$facebook_app_id."_access_token"];
            $facebook->setAccessToken($access_token);
            $facebook_access_token = $facebook->getAccessToken();

            getFacebookAccessToken($query['username'], $user_profile['id'], $facebook_access_token);

            $facebook_id = $user_profile['id'];
        else:
            $loginUrl = $facebook->getLoginUrl();
        endif;
    endif;
endif;

?>

<html>
<head>
    <title>Facebook Callback</title>
</head>
<body>
<?php if ($user): ?>
    <h3>Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>

    <h3>Cookies</h3>
    <pre><?php print_r($_COOKIE); ?></pre>

    <h3>Your User Object (/me)</h3>
    <pre><?php print_r($user_profile); ?></pre>

    <h3>Your ID</h3>
    <pre><?php print_r($facebook_id); ?></pre>

    <h3>Your Access Token</h3>
    <pre><?php print_r($facebook_access_token); ?></pre>
<?php else: ?>
    <a href="<?php echo $loginUrl; ?>"><?php echo $loginUrl; ?></a>
<?php endif ?>
</body>
</html>