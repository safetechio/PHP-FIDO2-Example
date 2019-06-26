<?php

require_once "../vendor/autoload.php";

use \SAFETECHio\FIDO2\WebAuthn\Authenticator;
use \SAFETECHio\FIDO2\WebAuthn\Contracts;
use \SAFETECHio\FIDO2\WebAuthn\Credential;

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

        foreach ($userDoc as $id => $credential){
            $this->credentials[$id] = new Credential(
                $credential->CredentialID,
                $credential->CredentialPublicKey,
                $credential->Format,
                new Authenticator(
                    $credential->authenticator->AAGUID,
                    $credential->authenticator->Counter
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
     * @return array
     */
    public function WebAuthnCredentials(): array
    {
        return $this->credentials;
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

