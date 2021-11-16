<?php

class Mysql extends mysqli
{
    private $connected = false;

    public function connected()
    {
       if (mysqli::connect() == null) {
           return "Соединение установлено";
       } else {
           return "Ошибка соединения";
       }
    }

    public function sel($sql, $arr = [])
    {
        $params = $arr['params'];
        $type = $arr['type'];
        $rows = $arr['rows'];
        $row = [];

        $mysqli = new mysqli("localhost", "root", "", "laba1");

        if ($stmt = $mysqli->prepare($sql)) {

        if (is_array($params)) {
            $stmt->bind_param($type, ...$params);
        } else {
            $stmt->bind_param($type, $params);
        }

        $stmt->execute();

        if (!empty($rows)) {
            foreach ($rows as $value) {
                $param[] = &$row[$value];
            }
            call_user_func_array(array($stmt, 'bind_result'), $param);
        }

        if (!empty($rows)) {
            $stmt->fetch();
        }
        
        $keys = '';
        foreach ($rows as $value) {
            $keys .= $value . " ";
        }
        $key = explode(' ', $keys, -1);

        $res = array_combine($key, $param);

        $stmt->close();

        return $res;
    } else {
        return false;
    }
        $mysqli->close();
    }
}