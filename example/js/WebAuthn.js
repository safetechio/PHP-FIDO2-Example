$(document).ready(function () {

    // check whether current browser supports WebAuthn
    if (!window.PublicKeyCredential) {
        alert("Error: this browser does not support WebAuthn");
    }
});

class SAFETECHioWebAuthnConfig {
    registerBeginEndpoint;
    registerCompleteEndpoint;
    authenticateBeginEndpoint;
    authenticateCompleteEndpoint;
}

let config = new SAFETECHioWebAuthnConfig();
config.registerBeginEndpoint = "RegisterBegin.php?username=";
config.registerCompleteEndpoint = "RegisterComplete.php?username=";

class SAFETECHioWebAuthn {

    constructor(config) {
        this.config = config;
    }

    // Base64 to ArrayBuffer
    static bufferDecode(value) {
        return Uint8Array.from(atob(value), c => c.charCodeAt(0));
    }

    // ArrayBuffer to URLBase64
    static bufferEncode(value) {
        return btoa(String.fromCharCode.apply(null, new Uint8Array(value)))
            .replace(/\+/g, "-")
            .replace(/\//g, "_")
            .replace(/=/g, "");
    }

    registerUser() {

        let username = $("#email").val();
        if (username === "") {
            alert("Please enter a username");
            return;
        }

        $.get(
            this.config.registerBeginEndpoint + username,
            null,
            function (data) {
                return data
            },
            'json')
            .then((credentialCreationOptions) => {

                credentialCreationOptions.publicKey.challenge = SAFETECHioWebAuthn.bufferDecode(credentialCreationOptions.publicKey.challenge);
                credentialCreationOptions.publicKey.user.id = SAFETECHioWebAuthn.bufferDecode(credentialCreationOptions.publicKey.user.id);
                if (credentialCreationOptions.publicKey.excludeCredentials) {
                    for (let i = 0; i < credentialCreationOptions.publicKey.excludeCredentials.length; i++) {
                        credentialCreationOptions.publicKey.excludeCredentials[i].id = SAFETECHioWebAuthn.bufferDecode(credentialCreationOptions.publicKey.excludeCredentials[i].id);
                    }
                }

                return navigator.credentials.create({
                    publicKey: credentialCreationOptions.publicKey
                });
            })
            .then((credential) => {

                let attestationObject = credential.response.attestationObject;
                let clientDataJSON = credential.response.clientDataJSON;
                let rawId = credential.rawId;

                let msg = JSON.stringify({
                    id: credential.id,
                    rawId: SAFETECHioWebAuthn.bufferEncode(rawId),
                    type: credential.type,
                    response: {
                        attestationObject: SAFETECHioWebAuthn.bufferEncode(attestationObject),
                        clientDataJSON: SAFETECHioWebAuthn.bufferEncode(clientDataJSON),
                    },
                });

                return $.post(
                    this.config.registerCompleteEndpoint + username,
                    msg,
                    function (data) {
                        return data
                    },
                    'json'
                )
            })
            .then((success) => {
                alert("successfully registered " + username + "!");
            })
            .catch((error) => {
                console.log(error);
                let err = JSON.parse(error.responseText);
                console.log(err);
                alert("failed to register '" + username + "'. \n error : " + err.error.message)
            })
    }
}