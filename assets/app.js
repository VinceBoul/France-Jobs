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

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

let map;

function initMap() {
	map = new google.maps.Map(document.getElementById("map"), {
		center: { lat: -34.397, lng: 150.644 },
		zoom: 8,
	});


	const center = { lat: 50.064192, lng: -130.605469 };
// Create a bounding box with sides ~10km away from the center point
	const defaultBounds = {
		north: center.lat + 0.1,
		south: center.lat - 0.1,
		east: center.lng + 0.1,
		west: center.lng - 0.1,
	};
	const input = document.getElementById("Job_name");
	const options = {
		bounds: defaultBounds,
		componentRestrictions: { country: "us" },
		fields: ["address_components", "geometry", "icon", "name"],
		strictBounds: false,
		types: ["establishment"],
	};
	const autocomplete = new google.maps.places.Autocomplete(input, options);
}

window.initMap = initMap;
