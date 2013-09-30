<?php

function newUser($username, $password, $email, $created, $type)
{
    global $coll;
    $coll->insert(array('username' => $username, 'password' => $password, 'email' => $email, 'created' => $created, 'type' => $type));
    return true;
}

function getPass($username, $password)
{
    global $coll;
    $res = $coll->findOne(array('username' => $username, 'password' => $password));
    if($res):
        return true;
    endif;
}

function cleanMemberSession($username, $remember_me)
{
    $_SESSION["username"]=$username;
    $_SESSION["loggedIn"]=true;
    $_SESSION['signature'] = md5($username . $_SERVER['HTTP_USER_AGENT']);

    if ($remember_me == "on"):
        setcookie("signature", md5($username . $_SERVER['HTTP_USER_AGENT']), time()+60*60*24*30);
    endif;
}

function flushMemberSession()
{
    unset($_SESSION["username"]);
    unset($_SESSION["loggedIn"]);
    unset($_SESSION["signature"]);
    session_destroy();
    return true;
}

function loggedIn()
{
    if(!empty($_SESSION['loggedIn'])):
        return true;
    else:
        return false;
    endif;
}