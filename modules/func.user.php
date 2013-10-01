<?php

function newUser($username, $password, $email, $time, $token)
{
    $type = "member";

    global $coll;
    $coll->insert(array('username' => $username, 'password' => $password, 'email' => $email, 'type' => $type, 'created' => $time, 'modified' => $time, 'token' => $token));
    return true;
}

//function getPass($username, $password)
//{
//    global $coll;
//    $query = $coll->findOne(array('username' => $username, 'password' => $password));
//    if($query):
//        return true;
//    endif;
//}

function cleanMemberSession($username, $remember_me)
{
    global $coll;
    $query = $coll->findOne(array('username' => $username));
    if($query):
        $_SESSION["loggedIn"] = true;
        $_SESSION["user"] = $query['_id'];
        $_SESSION["token"] = $query['token'];

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
    unset($_SESSION["user"]);
    unset($_SESSION["token"]);
    session_destroy();
    return true;
}

function loggedIn()
{
    global $coll;
    $query = $coll->findOne(array('_id' => new MongoID($_COOKIE['user']), 'token' => $_COOKIE['token']));

    if(!empty($_SESSION['loggedIn'])):
        return true;
    elseif ($query):
        return true;
    else:
        return false;
    endif;
}

?>