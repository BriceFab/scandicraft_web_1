/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/global.scss';
import '../css/ReactToastify.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
window.$ = $;
window.jQuery = $;

//cannot call from html
export default function isIE() {
    var ua = window.navigator.userAgent;
    var isIE = /MSIE|Trident/.test(ua);
    return isIE;
}

//export to dom
window.isIE = isIE;