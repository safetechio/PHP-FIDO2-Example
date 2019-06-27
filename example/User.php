<?php

use \Ramsey\Uuid\Uuid;
use \SAFETECHio\FIDO2\Tools\Tools;
use \SAFETECHio\FIDO2\WebAuthn\Authenticator;
use \SAFETECHio\FIDO2\WebAuthn\Contracts;
use \SAFETECHio\FIDO2\WebAuthn\Credential;

class User implements Contracts\User {

    /** @var \Filebase\Document $userDoc */
    protected $userDoc;

    /** @var Credential[] $credentials */
    protected $credentials;

    /**
     * User constructor.
     * @param \Filebase\Document $userDoc
     */
    protected function __construct(\Filebase\Document $userDoc)
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

    /**
     * @return \Filebase\Database
     * @throws \Filebase\Filesystem\FilesystemException
     */
    protected static function getDB()
    {
        return new \Filebase\Database([
            'dir' => './db/users'
        ]);
    }

    /**
     * @param string $id
     * @return User
     * @throws \Filebase\Filesystem\FilesystemException
     * @throws \Ramsey\Uuid\Exception\UnsatisfiedDependencyException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function FindOrCreate(string $id): User
    {
        static::validate($id);

        $users = static::getDB();
        $u = $users->get($id);
        if($u->uuid == null){
            $u->uuid = Uuid::uuid1()->toString();
            $u->name = $id;
            $u->display_name = $id;
            $u->icon = "";
            $u->save();
        }

        return new static($u);
    }

    /**
     * @param string $id
     * @return User
     * @throws Exception
     */
    public static function FindOrFail(string $id): User
    {
        static::validate($id);

        $users = static::getDB();
        $u = $users->get($id);
        if($u->uuid == null){
            throw new Exception("User for ID '$id' not found");
        }

        return new static($u);
    }

    /**
     * @param string $id
     * @throws Exception
     */
    public static function validate(string $id)
    {
        if($id == "" || $id == null){
            throw new Exception("Id must not be null or an empty string");
        }
    }

    /**
     * @return string
     */
    public function WebAuthnID(): string
    {
        return $this->userDoc->uuid;
    }

    /**
     * @return string
     */
    public function WebAuthnName(): string
    {
        return $this->userDoc->name;
    }

    /**
     * @return string
     */
    public function WebAuthnDisplayName(): string
    {
        return $this->userDoc->display_name;
    }

    /**
     * @return string
     */
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

    /**
     * @param Credential $credential
     */
    public function SaveCredential(Credential $credential)
    {
        $this->userDoc->credentials[Tools::base64u_encode($credential->ID)] = $credential;
        $this->userDoc->save();
    }
}