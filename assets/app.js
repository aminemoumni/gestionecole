/*
* Welcome to your app's main JavaScript file!
*
* We recommend including the built version of this JavaScript file
* (and its CSS file) in your base layout (base.html.twig).

*/
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/app.css';
import './styles/login.css';
import './styles/etudiant.css';
// import './styles/datatable.bootstrap5.min.css'

// start the Stimulus application

import './bootstrap';
//const swal = require('sweetalert2');
const $ = require('jquery');
global.$ = $;

require('bootstrap');
const dt = require( 'datatables.net-bs4');
const Swal = require('sweetalert2');
window.Swal = Swal;

import './login'
import './admin'
import './inscription'
import './classeEtudiant'
import './classeProffesseur'
import './professeur'
import './matiere'
import './classeEpreuve'
import './classeFrais'
import './profile'

$("body").on('click', ".overlayOuvrage, .xOuvrage", function() {
    $('.overlayOuvrage, .popOuvrage').hide();
})



