<?php

require_once "../vendor/autoload.php";

/**
 * @return \Filebase\Database
 * @throws \Filebase\Filesystem\FilesystemException
 */
function GetDBUsers()
{
    return new \Filebase\Database([
        'dir' => './db/users'
    ]);
}

/**
 * @param $data
 */
function WriteJSON($data)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($data);
}

