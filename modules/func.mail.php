<?php

include_once("config.php");

function sendMail($email, $old_email, $message_type)
{
    global $sendgrid_url;
    global $sendgrid_user;
    global $sendgrid_password;
    global $sendgrid_from;

    if ($message_type == "email-change"):
        $params = array(
            'api_user' => $sendgrid_user,
            'api_key' => $sendgrid_password,
            'to' => $email,
            'subject' => 'Your email address has changed',
            'html' => '<strong>Hello World!</strong>',
            'text' => 'This is a confirmation that you have changed your email address.',
            'from' => $sendgrid_from,
        );
        $session = curl_init($sendgrid_url);
        curl_setopt ($session, CURLOPT_POST, true);
        curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($session);
        curl_close($session);
        print_r($response);
        if (!empty($old_email)):
            $params = array(
                'api_user' => $sendgrid_user,
                'api_key' => $sendgrid_password,
                'to' => $old_email,
                'subject' => 'Your email address has changed',
                'html' => '<strong>Hello World!</strong>',
                'text' => 'This is a confirmation that you have changed your email address.',
                'from' => $sendgrid_from,
            );
            $session = curl_init($sendgrid_url);
            curl_setopt ($session, CURLOPT_POST, true);
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            curl_setopt($session, CURLOPT_HEADER, false);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($session);
            curl_close($session);
            print_r($response);
        endif;
    endif;

    if ($message_type == "password-change"):
        $params = array(
            'api_user' => $sendgrid_user,
            'api_key' => $sendgrid_password,
            'to' => $email,
            'subject' => 'Your password has changed',
            'html' => '<strong>Hello World!</strong>',
            'text' => 'This is a confirmation that you have changed your password.',
            'from' => $sendgrid_from,
        );
        $session = curl_init($sendgrid_url);
        curl_setopt ($session, CURLOPT_POST, true);
        curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($session);
        curl_close($session);
        print_r($response);
        if (!empty($old_email)):
            $params = array(
                'api_user' => $sendgrid_user,
                'api_key' => $sendgrid_password,
                'to' => $old_email,
                'subject' => 'Your password has changed',
                'html' => '<strong>Hello World!</strong>',
                'text' => 'This is a confirmation that you have changed your password.',
                'from' => $sendgrid_from,
            );
            $session = curl_init($sendgrid_url);
            curl_setopt ($session, CURLOPT_POST, true);
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            curl_setopt($session, CURLOPT_HEADER, false);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($session);
            curl_close($session);
            print_r($response);
        endif;
    endif;

    if ($message_type == "register"):
        $params = array(
            'api_user' => $sendgrid_user,
            'api_key' => $sendgrid_password,
            'to' => $email,
            'subject' => 'Thank you for registering',
            'html' => '<strong>Hello World!</strong>',
            'text' => 'This is a confirmation that you have registered.',
            'from' => $sendgrid_from,
        );
        $session = curl_init($sendgrid_url);
        curl_setopt ($session, CURLOPT_POST, true);
        curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($session);
        curl_close($session);
        print_r($response);
        if (!empty($old_email)):
            $params = array(
                'api_user' => $sendgrid_user,
                'api_key' => $sendgrid_password,
                'to' => $old_email,
                'subject' => 'Thank you for registering',
                'html' => '<strong>Hello World!</strong>',
                'text' => 'This is a confirmation that you have registered.',
                'from' => $sendgrid_from,
            );
            $session = curl_init($sendgrid_url);
            curl_setopt ($session, CURLOPT_POST, true);
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            curl_setopt($session, CURLOPT_HEADER, false);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($session);
            curl_close($session);
            print_r($response);
        endif;
    endif;
    return true;
}

?>