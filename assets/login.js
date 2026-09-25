// Attributes
const usernameInput = $('#username');
const passwordInput = $('#password');
const loginInputs = $('.login-input');
const fullLoginForm = $('#fullLogin');
const validateButton = $('#login');
const openedEye = $('#show-password');
const closedEye = $('#hide-password');
const lostPasswordDiv = $('#lost-password');
const clearUsernameButton = $('#clearbtn-username');
const clearPasswordButton = $('#clearbtn-password');
const usernameAlertImg = $('#alert-username');
const passwordAlertImg = $('#alert-password');
const usernameOrPasswordError = $("#username-or-password-haserror");
const recaptchaError = $("#recaptha-haserror");
const EMAIL_REGEX = /^(([^ÈÉéèàôâêîûùäëöïç'<>()[\]\\.,;:\s@\""]+(\.[^ÈÉéèàôâêîûùäëöïç'<>()[\]\\.,;:\s@\""]+)*)|(\"".+\""))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
// Gestion de l'affichage de la gateway dans le cas d'un back après login ou d'un ajout de la page de login en bookmark
// Code effectué directement au chargement de la page de Login.
// Récupération de l'URL courante
const url = new URL(window.location);
// Extraction et décodage du paramètre ReturnUrl
const returnUrlParam = url.searchParams.get('ReturnUrl');
let returnUrlDecodedParam = decodeURI(returnUrlParam);
returnUrlDecodedParam = 'http://localhost' + returnUrlDecodedParam;
// Création d'une URL à partir du paramètre ReturnUrl
const innerUrl = new URL(returnUrlDecodedParam);
// Extraction et décodage du paramètre redirect_uri
const redirectUriParam = innerUrl.searchParams.get('redirect_uri');
const redirectUriDecodedParam = decodeURI(redirectUriParam);
// Création d'une URL à partir du paramètre redirect_uri
const redirectUriUrl = new URL(redirectUriDecodedParam);
// Si c'est un client web, on renvoi vers l'origine, sinon si c'est un client natif comme sur un mobile on garde le deeplink complet
const isWebClient = redirectUriUrl.protocol === 'https:' || redirectUriUrl.protocol === 'http:';
const processedRedirectUrl = isWebClient ? redirectUriUrl.origin : redirectUriUrl.href;
if (isWebClient) {
    // Affectation de l'URL de Gateway au link
    $("#redirectLoginBtn").attr("href", processedRedirectUrl);
    $("#redirectLoginBtn").text(processedRedirectUrl);
    // Récupération d'un éventuel paramètre bookmark à true
    const bookmarkParam = url.searchParams.get('bookmark');
    const bookmarkBool = (bookmarkParam === 'true');
    // Si un paramètre à true est trouvé
    if (bookmarkBool) {
        // Création d'un booléen permettant de savoir si la page a été reload
        const pageAccessedByReload = ((window.performance.navigation && window.performance.navigation.type === 1) ||
            window.performance
                .getEntriesByType('navigation')
                .map((nav) => nav.type)
                .indexOf('reload') !== -1);
        // Récupération des éventuelles erreur sur la page
        // Si la page n'a pas été reload
        // et qu'il n'y a pas d'erreur sur la page
        if (!pageAccessedByReload && !usernameOrPasswordError.length && !recaptchaError.length) {
            document.getElementById("app-content-bookmark-div").classList.remove("hide");
            document.getElementById("app-content-div").classList.add("hide");
            const a = document.querySelector("a.RedirectUri");
            const redirectTime = +$("#redirectLoginBtn").data("redirect-time");
            if (a) {
                setTimeout(function () { window.location = a.href; }, redirectTime);
            }
        }
    }
}
// Ajout du paramètre bookmark
url.searchParams.set('bookmark', 'true');
window.history.replaceState({}, '', url);
// Methods
const checkUserNameFormat = () => {
    let usernameValue = usernameInput.val();
    let isInt = (Math.floor(+usernameValue) == usernameValue) && ($.isNumeric(usernameValue));
    isInt = isInt && (usernameValue <= 2147483647);
    let isEmail = (EMAIL_REGEX.test(usernameValue.toString()));
    if (isInt || isEmail) {
        return true;
    }
    else {
        return false;
    }
};
const showUsernameLabel = () => {
    $('#mailLabel').addClass("show");
};
const hideUsernameLabel = () => {
    $('#mailLabel').removeClass("show");
};
const showPasswordLabel = () => {
    $('#passwordLabel').addClass("show");
};
const hidePasswordLabel = () => {
    $('#passwordLabel').removeClass("show");
};
const showUsernameSuccess = () => {
    $('#usernameDiv').addClass("success");
};
const showUsernameRequiredAlert = () => {
    $('#usernameDiv').addClass("alert");
    $("#username-required-msg").removeClass("hide");
    showUsernameAlertImg();
};
const hideUsernameRequiredAlert = () => {
    $('#usernameDiv').removeClass("alert");
    $("#username-required-msg").addClass("hide");
    hideUsernameAlertImg();
};
const showUsernameFormatAlert = () => {
    $('#usernameDiv').addClass("alert");
    $("#username-format-msg").removeClass("hide");
};
const hideUsernameFormatAlert = () => {
    $('#usernameDiv').removeClass("alert");
    $("#username-format-msg").addClass("hide");
};
const showPasswordSuccess = () => {
    $('#passwordDiv').addClass("success");
};
const showPasswordRequiredAlert = () => {
    $('#passwordDiv').addClass("alert");
    $("#password-required-msg").removeClass("hide");
    showPasswordAlertImg();
};
const hidePasswordRequiredAlert = () => {
    $('#passwordDiv').removeClass("alert");
    $("#password-required-msg").addClass("hide");
    hidePasswordAlertImg();
};
const hideUsernameOrPasswordAlert = () => {
    usernameOrPasswordError.addClass("hide");
    $('#passwordDiv').removeClass("alert");
    $('#usernameDiv').removeClass("alert");
};
const showUsernameOrPasswordAlert = () => {
    usernameOrPasswordError.removeClass("hide");
};
const showClearUsernameButton = () => {
    clearUsernameButton.addClass("show");
};
const hideClearUsernameButton = () => {
    clearUsernameButton.removeClass("show");
};
const showUsernameAlertImg = () => {
    usernameAlertImg.addClass("show");
};
const hideUsernameAlertImg = () => {
    usernameAlertImg.removeClass("show");
};
const showClearPasswordButton = () => {
    clearPasswordButton.addClass("show");
};
const hideClearPasswordButton = () => {
    clearPasswordButton.removeClass("show");
};
const showPasswordAlertImg = () => {
    passwordAlertImg.addClass("show");
};
const hidePasswordAlertImg = () => {
    passwordAlertImg.removeClass("show");
};
const lostPasswordEventHandler = (changePasswordUrl, languageName) => {
    $.ajax({
        method: 'GET',
        url: changePasswordUrl,
        data: ""
    }).done(function (baseUrlAppSecurity) {
        let urlAppSecurity = baseUrlAppSecurity + "mail/";
        let usernameValue = usernameInput.val();
        if (usernameValue !== "") {
            let isEmail = (EMAIL_REGEX.test(usernameValue.toString()));
            if (isEmail) {
                urlAppSecurity += usernameValue;
            }
        }
        urlAppSecurity += "?lang=" + languageName;
        window.location.href = urlAppSecurity;
    });
};
const fullLoginFormSubmit = () => {
    fullLoginForm.off('submit');
    fullLoginForm.trigger('submit');
    $('#loader').removeClass("hidden");
    validateButton.addClass('active');
    validateButton.prop('disabled', true);
};
// Username Input Events
usernameInput.on('focus', () => {
    showUsernameLabel();
});
usernameInput.on('focusout', () => {
    hideUsernameLabel();
    if (usernameInput.val() === "") {
        hideUsernameFormatAlert();
        showUsernameRequiredAlert();
    }
    else {
        hideUsernameRequiredAlert();
        if (!checkUserNameFormat()) {
            showUsernameFormatAlert();
        }
    }
});
usernameInput.on('keyup', () => {
    const isIntOrEmail = checkUserNameFormat();
    $("#username-required-msg").addClass("hide");
    $("#username-format-msg").addClass("hide");
    if (usernameOrPasswordError.length && !usernameOrPasswordError.hasClass("hide")) {
        hideUsernameOrPasswordAlert();
    }
    if (usernameInput.val() === "") {
        showUsernameRequiredAlert();
        hideClearUsernameButton();
    }
    else {
        hideUsernameRequiredAlert();
        showClearUsernameButton();
        if (isIntOrEmail) {
            hideUsernameFormatAlert();
            showUsernameSuccess();
        }
        else {
            showUsernameFormatAlert();
        }
    }
});
clearUsernameButton.on('click', () => {
    hideClearUsernameButton();
    usernameInput.val("");
    usernameInput.trigger('blur');
    validateButton.prop('disabled', true);
});
// Password Input Events 
passwordInput.on('focus', () => {
    showPasswordLabel();
});
passwordInput.on('focusout', () => {
    hidePasswordLabel();
    if (passwordInput.val() === "") {
        showPasswordRequiredAlert();
    }
});
passwordInput.on('keyup', () => {
    $("#password-required-msg").addClass("hide");
    if (usernameOrPasswordError.length && !usernameOrPasswordError.hasClass("hide")) {
        hideUsernameOrPasswordAlert();
    }
    if (passwordInput.val() === "") {
        showPasswordRequiredAlert();
        hideClearPasswordButton();
    }
    else {
        hidePasswordRequiredAlert();
        showPasswordSuccess();
        showClearPasswordButton();
    }
});
clearPasswordButton.on('click', () => {
    hideClearPasswordButton();
    passwordInput.val("");
    passwordInput.trigger('blur');
    validateButton.prop('disabled', true);
});
openedEye.on('click', () => {
    closedEye.removeClass("hide");
    openedEye.addClass("hide");
    passwordInput.prop("type", "password");
});
closedEye.on('click', () => {
    openedEye.removeClass("hide");
    closedEye.addClass("hide");
    passwordInput.prop("type", "text");
});
const friendlyCaptchaCallback = (solution) => {
    checkValidateButton();
};
// Username and Password Events
const checkValidateButton = () => {
    let bypassRecaptcha = fullLoginForm.data('bypass-recaptcha') == "True";
    let userNameAndPasswordValid = (checkUserNameFormat() && passwordInput.val() !== "") || (passwordInput.is(":-webkit-autofill") && usernameInput.is(":-webkit-autofill"));
    if (!userNameAndPasswordValid) {
        validateButton.prop('disabled', true);
        return;
    }
    if (bypassRecaptcha) {
        checkValidationButtonNoCaptcha();
        return;
    }
    checkValidateButtonFriendlyCaptcha();
};
const checkValidateButtonFriendlyCaptcha = () => {
    // FriendlyCaptcha
    const friendlyCaptchaSolution = $('input[name="frc-captcha-solution"]').val().toString();
    if (friendlyCaptchaSolution && !friendlyCaptchaSolution.startsWith('.')) {
        validateButton.prop('disabled', false);
    }
    else {
        validateButton.prop('disabled', true);
    }
};
const checkValidationButtonNoCaptcha = () => {
    // NoCaptcha
    if ((checkUserNameFormat() && passwordInput.val() !== "") || (passwordInput.is(":-webkit-autofill") && usernameInput.is(":-webkit-autofill"))) {
        validateButton.prop('disabled', false);
    }
    else {
        validateButton.prop('disabled', true);
    }
};
loginInputs.on('input', checkValidateButton);
// Validate Button Events
validateButton.on('click', () => {
    $('#loader').removeClass("hidden");
    validateButton.addClass('active');
});
fullLoginForm.on('submit', (e) => {
    e.preventDefault();
    fullLoginFormSubmit();
});
// Lost Password Mobile and Desktop Events
lostPasswordDiv.on('click', () => {
    // Suppression du paramètre bookmark
    url.searchParams.delete('bookmark');
    window.history.replaceState({}, '', url);
    const changePasswordMobileUrl = lostPasswordDiv.data('url-change-password');
    const languageName = lostPasswordDiv.data('language-name');
    lostPasswordEventHandler(changePasswordMobileUrl, languageName);
});
// Information Modal Events
$("#information").on('click', () => {
    $("#modalInfo").addClass("modal-open");
    $("#modalInfo .modal-wrapper").addClass("smooth-open");
    $("#modalInfo .modal-wrapper").removeClass("smooth-close");
});
$(".closeButtonModalInfo").on('click', () => {
    $("#modalInfo").removeClass("modal-open");
    $("#modalInfo .modal-wrapper").removeClass("smooth-open");
    $("#modalInfo .modal-wrapper").addClass("smooth-close");
});
// Passkey information Modal Events
$("#passkey_informations").on('click', () => {
    $("#modalPasskeyInfo").addClass("modal-open");
    $("#modalPasskeyInfo .modal-wrapper").addClass("smooth-open");
    $("#modalPasskeyInfo .modal-wrapper").removeClass("smooth-close");
});
$(".closeButtonModalPasskeyInfo").on('click', () => {
    $("#modalPasskeyInfo").removeClass("modal-open");
    $("#modalPasskeyInfo .modal-wrapper").removeClass("smooth-open");
    $("#modalPasskeyInfo .modal-wrapper").addClass("smooth-close");
});
$("#lost-username").on('click', () => {
    window.location.href = 'FindUsername';
});
$('.back-section').on('click', () => {
    window.history.back();
});
//# sourceMappingURL=login.js.map