<?php

function getRefreshToken($username, $refresh_token)
{
    global $coll;
    $coll->update(array('username' => $username),
        array('$set' => array('account' => array('ga_refresh_token' => $refresh_token)
        )));
    return true;
}

function newUser($username, $password, $email, $token)
{
    $type = "member";

    global $coll;
    $coll->insert(array('username' => $username, 'password' => $password, 'email' => $email, 'type' => $type, 'created' => time(), 'modified' => time(), 'token' => $token));
    return true;
}

function passwordChange($username, $password, $token)
{
    global $coll;
    $coll->update(array('username' => $username),
        array('$set' => array('password' => $password, 'token' => $token, 'modified' => time()
        )));
    return true;
}

function emailChange($username, $email, $token)
{
    global $coll;
    $coll->update(array('username' => $username),
        array('$set' => array('email' => $email, 'token' => $token, 'modified' => time()
        )));
    return true;
}

function cleanMemberSession($username, $remember_me)
{
    global $coll;
    $query = $coll->findOne(array('username' => $username));
    if($query):
        $_SESSION["loggedIn"] = true;
        $_SESSION["username"] = $query['username'];

        if ($remember_me == "on"):
            setcookie("user", $query['_id'], time()+60*60*24*30);
            setcookie("token", $query['token'], time()+60*60*24*30);
        endif;
        return true;
    endif;
}

function flushMemberSession()
{
    unset($_SESSION["loggedIn"]);
    unset($_SESSION["username"]);
    session_destroy();

    setcookie("user", "", time()-3600);
    setcookie("token", "", time()-3600);
    return true;
}

function loggedIn()
{
    $query = array();

    if (!empty($_COOKIE['token'])):
        global $coll;
        $query = $coll->findOne(array('_id' => new MongoID($_COOKIE['user']), 'token' => $_COOKIE['token']));
    endif;

    if(!empty($_SESSION['loggedIn'])):
        return true;
    elseif (!empty($query)):
        $_SESSION["loggedIn"] = true;
        $_SESSION["username"] = $query['username'];
        return true;
    else:
        return false;
    endif;
}

?>