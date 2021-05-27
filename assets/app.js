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

$(document).ready(function(){

    // au clique sur burger menu = apparait -> disparait
    $('.istyle').hide();

    $('#burgerclick').on("click",()=>{
        $('.istyle').toggle();
    });

    // au clique sur burger menu = agrandit -> réduit
   
    $('#burgerclick').on("click",()=>{
        $('iconMenu').animate({
            
            width:'200px',
        
        } , 3000);
      
    })


});
