import {SAFETECHioWebAuthn, SAFETECHioWebAuthnConfig} from 'fido2_clientside';

let config = new SAFETECHioWebAuthnConfig();
config.registerBeginEndpoint += "../backend/RegisterBegin.php?username=";
config.registerCompleteEndpoint += "../backend/RegisterComplete.php?username=";
config.authenticateBeginEndpoint += "../backend/AuthenticateBegin.php?username=";
config.authenticateCompleteEndpoint += "../backend/AuthenticationComplete.php?username=";
config.usernameInputID = "#email";
config.giveErrorAlert = true;
config.giveSuccessAlert = true;

let SafeTechWebAuthn = new SAFETECHioWebAuthn(config);

export {config, SafeTechWebAuthn};