<?php

class Cookie
{
    public function set($name, $value = [])
    {
        setcookie($name, base64_encode(serialize($value)), time() + 3600 * 24 * 30);
    }

    public function get($name)
    {
        return unserialize(base64_decode($name));
    }

    public function del($name)
    {
        setcookie($name, '',time() - 3600);
    }
}