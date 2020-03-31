/* @BriceFab ScandiCraft JS Code non build par encore webpack (bug pour certaines librairies)  */
$('#reset_password').on('show.bs.modal', function (e) {
    enableBlur();
});

$('#reset_password').on('hidden.bs.modal', function (e) {
    disableBlur();
});

function enableBlur() {
    $('.header-v1').addClass('modal-blur');
    $('.sub-banner').addClass('modal-blur');
    $('.section').addClass('modal-blur');
    $('footer').addClass('modal-blur');
}

function disableBlur() {
    $('.header-v1').removeClass('modal-blur');
    $('.sub-banner').removeClass('modal-blur');
    $('.section').removeClass('modal-blur');
    $('footer').removeClass('modal-blur');
}