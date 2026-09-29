var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
document.getElementById('passwordless').addEventListener('click', () => handleSignInSubmit(null));
// Pour l'instant pas ouf le CMA, ça a l'air de fonctionner mais :
//  - Je peux pas mettre à la fois cet autocomplete ET le bouton.Il considère que le truc est déjà en cours d'utilisation quand y'a l'autocomplete donc le bouton marche pas. Du coup pas de  bulle d'aide ou quoi par ex.
//  - Il propose en priorité les mots de passe sur iOS j'ai l'impression.C'est pour ça que là tu vois pas ta clé
//  - Quand tu préremplies le champs Email, bah il te propose plus du tout la clé associée, il faut que les champs soient vides pour que ça marche
//$(async () => {
//    if (isWebAuthnAvailable) {
//        // Check if conditional mediation is available.
//        const isCMA = PublicKeyCredential.isConditionalMediationAvailable && await PublicKeyCredential.isConditionalMediationAvailable();
//        if (!isCMA) {
//            console.log("CMA unavailable on this webhost");
//            $('.passwordLessBlock').css('visibility', 'visible');
//        } else {
//            handleSignInSubmit(null);
//        }
//    }
//});
function isWebAuthnAvailable() {
    return window.PublicKeyCredential;
}
function coerceToArrayBuffer(thing) {
    try {
        if (typeof thing === "string") {
            // base64url to base64
            thing = thing.replace(/-/g, "+").replace(/_/g, "/");
            // base64 to Uint8Array
            let str = window.atob(thing);
            let bytes = new Uint8Array(str.length);
            for (let i = 0; i < str.length; i++) {
                bytes[i] = str.charCodeAt(i);
            }
            thing = bytes;
        }
        // Array to Uint8Array
        if (Array.isArray(thing)) {
            thing = new Uint8Array(thing);
        }
        // Uint8Array to ArrayBuffer
        if (thing instanceof Uint8Array) {
            thing = thing.buffer;
        }
        // error if none of the above worked
        if (!(thing instanceof ArrayBuffer)) {
            throw new TypeError("could not coerce to ArrayBuffer");
        }
    }
    catch (e) {
        console.log('ERROR', e);
    }
    return thing;
}
;
function coerceToBase64Url(thing) {
    // Array or ArrayBuffer to Uint8Array
    if (Array.isArray(thing)) {
        thing = Uint8Array.from(thing);
    }
    if (thing instanceof ArrayBuffer) {
        thing = new Uint8Array(thing);
    }
    // Uint8Array to base64
    if (thing instanceof Uint8Array) {
        let str = "";
        let len = thing.byteLength;
        for (let i = 0; i < len; i++) {
            str += String.fromCharCode(thing[i]);
        }
        thing = window.btoa(str);
    }
    if (typeof thing !== "string") {
        throw new Error("could not coerce to string");
    }
    // base64 to base64url
    // NOTE: "=" at the end of challenge is optional, strip it off here
    thing = thing.replace(/\+/g, "-").replace(/\//g, "_").replace(/=*$/g, "");
    return thing;
}
;
function handleSignInSubmit(username) {
    return __awaiter(this, void 0, void 0, function* () {
        if (!isWebAuthnAvailable()) {
            console.log("WebAuthn unavailable on this webhost");
            return;
        }
        // Check if conditional mediation is available.
        //const isCMA = PublicKeyCredential.isConditionalMediationAvailable && await PublicKeyCredential.isConditionalMediationAvailable();
        if (!username) {
            username = $('#username').val().toString();
        }
        // prepare form post data
        let formData = new FormData();
        formData.append('username', username);
        // send to server for registering
        let makeAssertionOptionsResult, makeAssertionOptions;
        try {
            let res = yield fetch('/assertion/options', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });
            makeAssertionOptionsResult = yield res.json();
        }
        catch (e) {
            console.error("Request to server failed", e);
        }
        console.log("Assertion Options Object", makeAssertionOptionsResult);
        makeAssertionOptions = makeAssertionOptionsResult.value;
        makeAssertionOptions.challenge = coerceToArrayBuffer(makeAssertionOptions.challenge);
        makeAssertionOptions.allowCredentials.forEach(function (listItem) {
            listItem.id = coerceToArrayBuffer(listItem.id);
        });
        console.log("Assertion options", makeAssertionOptions);
        // ask browser for credentials (browser will ask connected authenticators)
        let credential;
        try {
            credential = yield navigator.credentials.get({ mediation: /*isCMA ? "conditional" :*/ "optional", publicKey: makeAssertionOptions });
        }
        catch (err) {
            console.error(err.message ? err.message : err);
            $('#fido-getcreds-error').show();
            document.cookie = "TriggerPasswordless=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            return;
        }
        try {
            yield verifyAssertionWithServer(makeAssertionOptionsResult.correlationId, credential);
        }
        catch (e) {
            console.error("Could not verify assertion", e);
        }
    });
}
/**
 * Sends the credential to the the FIDO2 server for assertion
 * @param {any} assertedCredential
 */
function verifyAssertionWithServer(correlationId, assertedCredential) {
    return __awaiter(this, void 0, void 0, function* () {
        // Move data into Arrays incase it is super long
        let authData = new Uint8Array(assertedCredential.response.authenticatorData);
        let clientDataJSON = new Uint8Array(assertedCredential.response.clientDataJSON);
        let rawId = new Uint8Array(assertedCredential.rawId);
        let sig = new Uint8Array(assertedCredential.response.signature);
        const data = {
            id: assertedCredential.id,
            rawId: coerceToBase64Url(rawId),
            type: assertedCredential.type,
            extensions: assertedCredential.getClientExtensionResults(),
            response: {
                authenticatorData: coerceToBase64Url(authData),
                clientDataJson: coerceToBase64Url(clientDataJSON),
                signature: coerceToBase64Url(sig)
            }
        };
        // @ts-ignore
        $('#username').rules('remove');
        // @ts-ignore
        $('#password').rules('remove');
        $('#fido-assertion-data').val(JSON.stringify(data));
        $('#fido-correlation-id').val(correlationId);
        $('#fullLogin').trigger('submit');
    });
}
//# sourceMappingURL=fido.js.map