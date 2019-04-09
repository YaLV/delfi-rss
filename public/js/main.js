var errorContainer = jQuery('<span>').addClass('invalid-feedback').attr('role', 'alert');
var alerted = false;

// Setup default ajax responses
jQuery.ajaxSetup({
    beforeSend: function (xhr) {
        csrf_token = window.token;
        xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token)
    },
    error: function (xhr, textStatus, error) {

        jQuery('.is-invalid').removeClass('is-invalid');
        jQuery('.invalid-feedback').remove();

        // Validation Exception
        if (xhr.status === 422) {
            focusField = '';
            message = xhr.responseJSON.errors;
            for (x in message) {
                errField = jQuery('#'+x);
                if (errField.length) {
                    errorContent = errorContainer.clone();
                    if(message.hasOwnProperty(x)) {
                        errorMessage = jQuery('<strong>').text(message[x]);
                        errorContent.html(errorMessage);
                        errField.addClass('is-invalid');
                        errField.parent().append(errorContent);
                        focusField = focusField||errField;
                    }
                }
            }
            if(jQuery('#registerForm').length) {
                jQuery('#password, #password-confirm').val('');
                focusField.focus();
            }
            return true;
        }

        if (xhr.status === 500) {
            if(!alerted) {
                alerted = true;
                alert('There was an error with Ajax request, please Contact administrator');
            }
        }

        if (xhr.status === 419) {
            if(!alerted) {
                alerted = true;
                alert('Verification Token not present, or invalid, please refresh your session');
            }
        }

        return true;
    },
    complete: function (xhr, textStatus, error) {
        if (xhr.status === 200) {
            jQuery('.is-invalid').removeClass('is-invalid');
            jQuery('.invalid-feedback').remove();
            if(xhr.hasOwnProperty('responseJSON')) {
                if (xhr.responseJSON.hasOwnProperty('redirect')) {
                    document.location = xhr.responseJSON.redirect;
                }
            }
        }
    }
});

// Append _token to every post
jQuery.ajaxPrefilter(function (options, originalOptions, jqXHR) {
    if (options.type.toLowerCase() === "post") {
        csrf_token = window.token;
        if (typeof options.data === "object") {
            options.data.append('_token', encodeURIComponent(csrf_token));
        } else {
            options.data = options.data || "";
            options.data += options.data ? "&" : "";
            options.data += "_token=" + encodeURIComponent(csrf_token);
        }
    }
});