/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import 'lightbox2/dist/js/lightbox.min';

import 'jquery-sticky/jquery.sticky';
/*
import 'owl.carousel/dist/assets/owl.carousel.css';
import 'owl.carousel';
*/
const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');


$(document).ready(function (){

	$(".infos-pratiques").sticky({topSpacing:50});
	$(".coordonnes").sticky({topSpacing:50});

	$('.coordonnes').on('sticky-start', function() {
		$(".infos-pratiques").unstick();
	});

	$('.coordonnes').on('sticky-end', function() {
		$(".infos-pratiques").sticky();
	});

	$( window ).scroll(function() {
		$(".coordonnes").sticky('update');
	});
});

let map;
/*
function initMap() {
	map = new google.maps.Map(document.getElementById("map"), {
		center: { lat: -34.397, lng: 150.644 },
		zoom: 8,
	});

}

window.initMap = initMap;*/
