(function ($) {
    jQuery(document).ready(function ($) {
        $('#zoom-register-form').on('submit', function (e) {
            e.preventDefault();

            var form = $(this);
            var responseDiv = $('#zoom-register-response');
            responseDiv.html('Processing...');

            $.ajax({
                url: ajax_variables.ajax_url,
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    if (res.success) {
                        responseDiv.html('Registration successful! Redirecting...');
                        window.location.href = res.data.redirect;
                    } else {
                        responseDiv.html('<span style="color:red;">' + res.data + '</span>');
                    }
                },
                error: function () {
                    responseDiv.html('<span style="color:red;">Something went wrong.</span>');
                }
            });
        });

        $('#zoom-login-form').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const responseDiv = $('#zoom-login-response');
            responseDiv.html('Logging in...');

            $.ajax({
                url: ajax_variables.ajax_url,
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    if (res.success) {
                        responseDiv.html('Login successful! Redirecting...');
                        window.location.href = res.data.redirect;
                    } else {
                        responseDiv.html('<span style="color:red;">' + res.data + '</span>');
                    }
                },
                error: function () {
                    responseDiv.html('<span style="color:red;">Something went wrong.</span>');
                }
            });
        });
    });
}(jQuery))