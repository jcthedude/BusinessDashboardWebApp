<?php

function newUser($username, $password, $email, $created, $type)
{
    global $coll;
    $coll->insert(array('username' => $username, 'password' => md5($password), 'email' => $email, 'created' => $created, 'type' => $type));
    return true;
}

function checkPass($username, $password)
{
    global $coll;
    $res = $coll->findOne(array('username' => $username, 'password' => md5($password)));
    if($res):
        return true;
    endif;
}

function cleanMemberSession($username)
{

    $_SESSION["username"]=$username;
    $_SESSION["loggedIn"]=true;
}

function flushMemberSession()
{
    unset($_SESSION["username"]);
    unset($_SESSION["loggedIn"]);
    session_destroy();
    return true;
}

function loggedIn()
{
    if($_SESSION['loggedIn']):
        return true;
    else:
        return false;
    endif;
}