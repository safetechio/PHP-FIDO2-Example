<?php

require_once "../vendor/autoload.php";

// TODO refactor all User related funcs into dedicated User class file, include DB connection in User also
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

function WrapUser(\Filebase\Document $user)
{
    return new User($user);
}

/**
 * @param $data
 */
function WriteJSON($data)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($data);
}

