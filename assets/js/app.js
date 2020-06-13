/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// Global css
import '../css/global.scss';

// Jquery
import $ from 'jquery';
window.$ = $;
window.jQuery = $;

//Bootstrap js
import '../../node_modules/bootstrap/js/dist/modal';

//Other app function
import './modal_blur';
import './share/index';

//cannot call from html
export default function isIE() {
    var ua = window.navigator.userAgent;
    var isIE = /MSIE|Trident/.test(ua);
    return isIE;
}

//export to dom
window.isIE = isIE;