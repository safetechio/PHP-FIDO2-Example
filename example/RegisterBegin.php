<?php

require_once "../vendor/autoload.php";
require_once "functions.php";

use SAFETECHio\FIDO2\WebAuthn;

try{
    $WebAConfig = new WebAuthn\WebAuthnConfig(
        "SAFETECHio PHP FIDO2 Example",
        "http://localhost:8082",
        "http://localhost:8082"          // Optional
        //"https://example.com/images/logo.png" // Optional
    );
    $WebA = new WebAuthn\WebAuthnServer($WebAConfig);

    $users = GetDBUsers();

    // Begin Registration

    // create or find the registering user from your data store
    $user = $users->get($_GET["username"]);

    // TODO wrap the DB user in class that followers the SAFETECHio\FIDO2\WebAuthn\Contracts\User interface
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