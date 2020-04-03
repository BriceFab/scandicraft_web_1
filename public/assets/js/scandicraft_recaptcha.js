//TODO @BriceFab pass in paramter script
const siteKey = '6LducuYUAAAAAGTDnDb1rpGmu5lQy_rgsq7uMRe3';

//either on page load
grecaptcha.ready(function() {
    grecaptcha.execute(siteKey, {
        action: 'homepage'
    }).then(function(token) {
        //the token will be sent on form submit
        $('[name="captcha"]').val(token);
        //keep in mind that token expires in 120 seconds so it's better to add setTimeout.
    });
});

//or on form post:
grecaptcha.ready(function() {
    grecaptcha.execute(siteKey, {
        action: 'homepage'
    }).then(function(token) {
        //submit the form
        return http.post(url, {email, captcha: token});
    });
});