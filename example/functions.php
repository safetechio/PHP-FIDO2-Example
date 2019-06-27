<?php

require_once "../vendor/autoload.php";

/**
 * @param $data
 */
function WriteJSON($data)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($data);
}
