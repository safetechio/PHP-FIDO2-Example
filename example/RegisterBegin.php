<?php

require_once "../vendor/autoload.php";
require_once "functions.php";

use SAFETECHio\FIDO2\WebAuthn;
use Ramsey\Uuid\Uuid;

try{
    // Init WebAuthn Server
    $WebAConfig = new WebAuthn\WebAuthnConfig(
        "SAFETECHio PHP FIDO2 Example",
        "http://localhost",
        "http://localhost"
        //"https://example.com/images/logo.png"
    );
    $WebA = new WebAuthn\WebAuthnServer($WebAConfig);

    // Begin Registration

    // create or find the registering user from your data store
    $users = GetDBUsers();

    $u = $users->get($_GET["username"]);
    $u->uuid = Uuid::uuid1()->toString();
    $u->name = $_GET["username"];
    $u->display_name = "";
    $u->icon = "";

    $user = WrapUser($u);

    list($options, $sessionData) = $WebA->BeginRegistration($user)->Make();

    // sessionData should be saved in the registration session
    session_start();
    $_SESSION['registration_session'] = $sessionData;

    WriteJSON($options);
    // respond with the options
    // options->publicKey contains the registration options
} catch (Throwable $exception) {
    var_dump($exception);
}