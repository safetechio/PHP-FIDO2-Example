<?php

require_once "../vendor/autoload.php";
require_once "functions.php";

use SAFETECHio\FIDO2\WebAuthn;

try{
    // Init WebAuthn Server
    $WebA = InitWebAuthn();

    // Begin Registration

    // create or find the registering user from your data store
    $user = User::FindOrCreate($_GET["username"]);

    /**
     * @var WebAuthn\Protocol\Options\CredentialCreation $options
     * @var WebAuthn\SessionData $sessionData
     */
    list($options, $sessionData) = $WebA->BeginRegistration($user)->Make();

    // sessionData should be saved in the registration session
    session_start();
    $_SESSION['registration_session'] = $sessionData;

    // respond with the options
    // options->publicKey contains the registration options
    WriteJSON($options);
} catch (Throwable $exception) {
    // TODO return JSON Error rather than var_dump
    var_dump($exception);
}