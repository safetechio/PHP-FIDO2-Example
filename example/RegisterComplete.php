<?php

require_once "../vendor/autoload.php";
require_once "functions.php";

try{
    // Init WebAuthn Server
    $WebA = InitWebAuthn();

    // Complete Registration
    // find the registering user from your data store
    $user = User::FindOrFail($_GET["username"]);

    // Get the session data stored in the beginRegistration step
    session_start();
    $sessionData = $_SESSION['registration_session'];

    // Get the incoming JSON data
    $jsonResponse = file_get_contents('php://input');

    // Call the WebAuthn->completeRegistration() func
    $credential = $WebA->completeRegistration($user, $sessionData, $jsonResponse);

    // If creation was successful, store the credential object
    $user->SaveCredential($credential);

    // Destroy the registration session
    unset($_SESSION['registration_session']);

    // Respond with a success message
    WriteJSON("Registration Success");
} catch (Throwable $exception) {
    // TODO return JSON Error rather than var_dump
    var_dump($exception);
}