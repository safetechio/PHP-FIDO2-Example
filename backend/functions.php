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
 * @param int $status
 */
function MakeJSONResponse($data, $status = 200)
{
    header('Content-type:application/json;charset=utf-8');

    if(is_a($data, Throwable::class)){
        if($status == 200){
            http_response_code(400);
        }

        /** @var Throwable $data */
        $out = [
            "error" => [
                "message" => $data->getMessage(),
                "line" => $data->getLine(),
                "file" => $data->getFile(),
                "code" => $data->getCode(),
            ]
        ];
        $json = json_encode($out);
        echo $json;
        return;
    }

    http_response_code($status);
    echo json_encode($data);
}
