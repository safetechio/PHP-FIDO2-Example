<?php

require_once "../vendor/autoload.php";

use SAFETECHio\FIDO2\WebAuthn;

/**
 * @param WebAuthn\WebAuthnConfig $WebAConfig
 * @return WebAuthn\WebAuthnServer
 * @throws \SAFETECHio\FIDO2\Exceptions\WebAuthnException
 */
function InitWebAuthn(WebAuthn\WebAuthnConfig $WebAConfig = null): WebAuthn\WebAuthnServer
{
    if($WebAConfig == null) {
        $WebAConfig = new WebAuthn\WebAuthnConfig(
            "SAFETECHio PHP FIDO2 Example",
            "localhost",
            "http://localhost:8082"
            //"https://example.com/images/logo.png"
        );
    }
    return new WebAuthn\WebAuthnServer($WebAConfig);
}

/**
 * @param $data
 */
function WriteJSON($data)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($data);
}
