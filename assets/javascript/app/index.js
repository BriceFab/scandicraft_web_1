// Jquery
import $ from 'jquery';
//Bootstrap js
import 'bootstrap/js/dist/modal';
//Other app function
import './modal_blur';
import './share/spoil';

window.$ = $;
window.jQuery = $;

//cannot call from html
export default function isIE() {
    const ua = window.navigator.userAgent;
    return /MSIE|Trident/.test(ua);
}

//export to dom
window.isIE = isIE;