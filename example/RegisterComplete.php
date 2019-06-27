<?php

require_once "../vendor/autoload.php";
require_once "functions.php";

use SAFETECHio\FIDO2\WebAuthn;

try{
    // TODO move init into main func file
    // Init WebAuthn Server
    $WebAConfig = new WebAuthn\WebAuthnConfig(
        "SAFETECHio PHP FIDO2 Example",
        "localhost",
        "http://localhost:8082"
    //"https://example.com/images/logo.png"
    );
    $WebA = new WebAuthn\WebAuthnServer($WebAConfig);

    // Complete Registration
    // find the registering user from your data store
    $users = GetDBUsers();
    $u = $users->get($_GET["username"]);
    $user = WrapUser($u);

    // Get the session data stored in the beginRegistration step
    session_start();
    $sessionData = $_SESSION['registration_session'];

    // Get the incoming JSON data
    $jsonResponse = file_get_contents('php://input');

    // Call the WebAuthn->completeRegistration() func
    $credential = $WebA->completeRegistration($user, $sessionData, $jsonResponse);

    // If creation was successful, store the credential object
    $user->WebAuthnSaveCredential($credential);

    // Destroy the registration session
    unset($_SESSION['registration_session']);

    // Respond with a success message
    WriteJSON("Registration Success");
} catch (Throwable $exception) {
    // TODO return JSON Error rather than var_dump
    var_dump($exception);
}