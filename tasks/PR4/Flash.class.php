<?php

require_once 'Session.class.php';

$session = new Session();

class Flash
{
    public function setMessage($message)
    {
        $_SESSION['message'] = $message;
        if ($_SESSION['message']) {
            return true;
        } else {
            return false;
        }
    }

    public function getMessage()
    {
        return $_SESSION['message'];
    }
}