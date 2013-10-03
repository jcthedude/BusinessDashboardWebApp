<?php

include_once("config.php");

function sendMail($email, $old_email, $message_type)
{
    global $headers;
    global $subject_emailchange;
    global $message_emailchange;

    if ($message_type == "email-change"):
        $subject = $subject_emailchange;
        $message = $message_emailchange;
        mail($email,$subject,$message,$headers);
        if (!empty($old_email)):
            mail($old_email,$subject,$message,$headers);
        endif;
    endif;
    return true;
}

?>