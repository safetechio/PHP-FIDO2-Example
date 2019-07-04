<?php

require_once "../vendor/autoload.php";
require_once "functions.php";

use SAFETECHio\FIDO2\WebAuthn;

try{
    // Init WebAuthn Server
    $WebA = InitWebAuthn();

    // Begin Authentication
    // find the registering user from your data store
    $user = User::FindOrFail($_GET["username"]);

    /** @var $WebA WebAuthn\WebAuthnServer */
    list($options, $sessionData) = $WebA->beginAuthentication($user)->Make();

    // sessionData should be saved in the authentication session
    session_start();
    $_SESSION['authentication_session'] = $sessionData;

    // respond with the options
    // options->publicKey contains the registration options
    MakeJSONResponse($options);
} catch (Throwable $exception) {
    MakeJSONResponse($exception);
}

