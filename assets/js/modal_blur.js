document.addEventListener('DOMContentLoaded', function () {
    //register modal blur
    const modals = $('.modal').toArray();
    modals.forEach((modal) => {
        $(modal).on('show.bs.modal', function (e) {
            enableBlur();
        });
    
        $(modal).on('hidden.bs.modal', function (e) {
            disableBlur();
        });
    })
}, false);

let previousHeaderZindex = '';
let previousLogoZindex = '';

function enableBlur() {
    const header = $('.header-v1');
    header.addClass('modal-blur');
    previousHeaderZindex = header[0].style.zIndex;
    header[0].style.zIndex = "1";
    $('.sub-banner').addClass('modal-blur');
    $('.section').addClass('modal-blur');
    $('footer').addClass('modal-blur');
    const logo = $('.login-logo')[0];
    previousLogoZindex = logo.style.zIndex;
    logo.style.zIndex = "500";
}

function disableBlur() {
    const header = $('.header-v1');
    header.removeClass('modal-blur');
    header[0].style.zIndex = previousHeaderZindex;
    $('.sub-banner').removeClass('modal-blur');
    $('.section').removeClass('modal-blur');
    $('footer').removeClass('modal-blur');
    const logo = $('.login-logo')[0];
    logo.style.zIndex = previousLogoZindex;
}

//export to dom
window.disableBlur = disableBlur;
window.enableBlur = enableBlur;