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
        // Remove objects from the trace because they can contain some complex JSON breaking data.
        $traces = $data->getTrace();
        foreach ($traces as $i => $trace){
            foreach ($trace["args"] as $x => $arg) {
                if(gettype($arg) == "object"){
                    $traces[$i]["args"][$x] = "(object) " . get_class($arg);
                }
            }
        }

        $out = [
            "error" => [
                "message" => $data->getMessage(),
                "trace" => $traces
            ]
        ];
        echo json_encode($out);
        return;
    }

    http_response_code($status);
    echo json_encode($data);
}
