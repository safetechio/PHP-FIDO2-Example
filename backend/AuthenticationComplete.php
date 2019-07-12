<?php

require_once "../vendor/autoload.php";
require_once "functions.php";

use SAFETECHio\FIDO2\WebAuthn;

try{
    // Init WebAuthn Server
    $WebA = InitWebAuthn();

    // Complete Authentication
    // find the registering user from your data store
    $user = User::FindOrFail($_GET["username"]);

    // Get the authentication session data stored in the beginAuthentication step
    session_start();
    $sessionData = $_SESSION['authentication_session'];

    // Get the incoming JSON data
    $jsonResponse = file_get_contents('php://input');

    /** @var $WebA WebAuthn\WebAuthnServer */
    $WebA->completeAuthentication($user, $sessionData, $jsonResponse);

    // Destroy the registration session
    unset($_SESSION['authentication_session']);

    // Respond with a success message
    MakeJSONResponse("Registration Success");
} catch (Throwable $exception) {
    MakeJSONResponse($exception);
}