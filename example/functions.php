<?php

require_once "../vendor/autoload.php";

use \SAFETECHio\FIDO2\Tools\Tools;
use \SAFETECHio\FIDO2\WebAuthn\Authenticator;
use \SAFETECHio\FIDO2\WebAuthn\Contracts;
use \SAFETECHio\FIDO2\WebAuthn\Credential;

// TODO refactor all User related funcs into dedicated User class file, include DB connection in User also
/**
 * @return \Filebase\Database
 * @throws \Filebase\Filesystem\FilesystemException
 */
function GetDBUsers()
{
    return new \Filebase\Database([
        'dir' => './db/users'
    ]);
}

function WrapUser(\Filebase\Document $user)
{
    return new User($user);
}

class User implements Contracts\User {

    /** @var \Filebase\Document $userDoc */
    protected $userDoc;

    /** @var Credential[] $credentials */
    protected $credentials;

    public function __construct(\Filebase\Document $userDoc)
    {
        $this->userDoc = $userDoc;

        foreach ($userDoc->credentials as $id => $credential){
            $credential = json_decode(json_encode($credential));

            $this->credentials[$id] = new Credential(
                Tools::base64u_decode($credential->ID),
                Tools::base64u_decode($credential->PublicKey),
                $credential->AttestationType,
                new Authenticator(
                    Tools::base64u_decode($credential->Authenticator->AAGUID),
                    $credential->Authenticator->SignCount
                )
            );
        }
    }

    public function WebAuthnID(): string
    {
        return $this->userDoc->uuid;
    }

    public function WebAuthnName(): string
    {
        return $this->userDoc->name;
    }

    public function WebAuthnDisplayName(): string
    {
        return $this->userDoc->display_name;
    }

    public function WebAuthnIcon(): string
    {
        return $this->userDoc->icon;
    }

    /**
     * @return Credential[]
     */
    public function WebAuthnCredentials(): array
    {
        return $this->credentials;
    }

    public function WebAuthnSaveCredential(Credential $credential)
    {
        $this->userDoc->credentials[Tools::base64u_encode($credential->ID)] = $credential;
        $this->userDoc->save();
    }
}

/**
 * @param $data
 */
function WriteJSON($data)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($data);
}

