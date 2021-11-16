<?php

class Session
{
    function __construct()
    {
        session_start();
    }

    public function set($row, $value)
    {
        $_SESSION[$row] = $value;
    }

    public function get($row)
    {
        return $_SESSION[$row];
    }

    public function del($row)
    {
        unset($_SESSION[$row]);
    }

    public function isReal($row)
    {
        if ($_SESSION[$row]) {
            return true;
        } else {
            return false;
        }
    }

}