var timeout;

jQuery(document).ready(function () {
    jQuery('#email').keyup(function () {
        clearTimeout(timeout);
        timeout = setTimeout(checkEmail, 700);
    }).blur(function () {
        clearTimeout(timeout);
        checkEmail();
    });

    jQuery('#registerForm').submit(function (e) {
        jQuery.post($(this).attr('action'), $(this).serialize(), function(response) {
            console.log('asdasdas');
        });
        e.preventDefault();
        return false;
    });
});

var checkEmail = function () {
    verifyField = jQuery('#email');
    jQuery.post(verifyField.data('verifyurl'), "email="+verifyField.val());
};
