/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

const $ = require("jquery");
global.$ = global.jQuery = $;
window.Popper = require("popper.js");
require("bootstrap");

// start the Stimulus application
console.log("chargement du fichier app.js");
