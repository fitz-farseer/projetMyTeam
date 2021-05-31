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
   
});

/* ---------------------------------------------------------- */

// au clique sur burger menu = agrandit -> réduit la largeur de iconMenu
const burgerclick = document.getElementById('burgerclick');
const iconmenu = document.querySelector('.iconMenu');

burgerclick.addEventListener('click', () => {
    iconmenu.classList.toggle('agrandissement');
});

/* ---------------------------------------------------------- */

//Tableau responsive
document.addEventListener('DOMContentLoaded', function(){ // au lancement du document
    //on récupère tous les textes des "th (thead)" de chaque "tableau responsive" pour les mettre dans un tableau "labels"
    document.querySelectorAll('.tableResponsive').forEach(function(table){
        let labels = [];
        table.querySelectorAll('th').forEach(function(th){
            labels.push(th.innerText);
        });
        // pour chaque "td (ligne)" dans le tableau, on récupère l'index du td
        //puis on va récupérer l'index du td et mettre le data qui correspond
        table.querySelectorAll('td').forEach(function(td, i){
            td.setAttribute('data-label', labels[i % labels.length]);
        }); 
    });
});