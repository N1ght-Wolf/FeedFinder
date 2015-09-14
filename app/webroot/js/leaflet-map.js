var map, world, adminOne, ukAdminThree;
var layers = [];
var simpCounter = 0;
var geoserverUrl = 'http://localhost:8080/geoserver/cite/wms';
var mapToken = 'pk.eyJ1IjoiZmVlZC1maW5kZXIiLCJhIjoiMDIyMGI4ZmU4ZmFlYTMxMDFlMjYyZmJmNzQ5OWJhOGEifQ.6cOhRAs3U0blI_n-cJxD0g';
var sidebar;
var customMarker;
var allowToZoom = true;
var reviewRating;
var reviewCount;
$(document).ready(function() {
	reviewRating = new L.LayerGroup();
	reviewCount = new L.LayerGroup();


	 L.marker([39.61, -105.02]).bindPopup('Littleton, CO.').addTo(reviewRating);
	 L.marker([39.74, -104.99]).bindPopup('Denver, CO.').addTo(reviewRating);
	 L.marker([39.73, -104.8]).bindPopup('Aurora, CO.').addTo(reviewRating);
	 L.marker([39.77, -105.23]).bindPopup('Golden, CO.').addTo(reviewRating);

 	var street =	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
		maxZoom: 18,
		id: 'davidoyeku.n73bd296',
		accessToken: mapToken
	});

	map = L.map('map',{
      center: [51.505, -0.09],
      zoom: 5,
      layers: [street, reviewRating]
    });
    // // Make the "Landmarks" group exclusive (use radio inputs)


		map.on('overlayadd', onOverlayAdd);

		function onOverlayAdd(e){
		    console.log(e);
		}
	// mapZoomed();
	geocoder = L.Control.geocoder().addTo(map);
	sidebar = L.control.sidebar('sidebar');
	sidebar.addTo(map);

	customMarker = L.Marker.extend({
   options: {
   }
});



});



function removeMapLayer(layer) {
	if (map.hasLayer(layer)) {
		map.removeLayer(layer);
	}
}

function mapZoomed() {
	map.on('zoomend', function(e) {
		if (allowToZoom) {
			if (map.getZoom() >= 6 && map.getZoom() <= 10) {
				if (simpCounter == 0 || simpCounter == 2) {
					if (map.hasLayer(world)) {
						map.removeLayer(world);
					}
					if (map.hasLayer(ukAdminThree)) {
						map.removeLayer(ukAdminThree);
					}
					adminOne.addTo(map);
					simpCounter = 1;
				}
			} else if (map.getZoom() >= 11) {
				if (simpCounter == 0 || simpCounter == 1) {
					simpCounter = 2;
					if (map.hasLayer(adminOne)) {
						map.removeLayer(adminOne);
					}
					ukAdminThree.addTo(map)
				}
			} else if (map.getZoom() <= 6) { //Return to original data
				if (simpCounter == 1 || simpCounter == 2) {
					if (map.hasLayer(adminOne)) {
						map.removeLayer(adminOne);
					}
					world.addTo(map);
					simpCounter = 0;
				}
			}
		}
	});
}

function disableMapInteraction() {
	map.dragging.disable();
	map.touchZoom.disable();
	map.doubleClickZoom.disable();
	map.scrollWheelZoom.disable();
	map.boxZoom.disable();
	map.keyboard.disable();
	if (map.tap) map.tap.disable();
	document.getElementById('map').style.cursor = 'default';
}

function enableMapInteraction() {
	map.dragging.enable();
	map.touchZoom.enable();
	map.doubleClickZoom.enable();
	map.scrollWheelZoom.enable();
	map.boxZoom.enable();
	map.keyboard.enable();
	if (map.tap) map.tap.enable();
	document.getElementById('map').style.cursor = 'grab';
}

function removeChoroplethLayers(){
	console.log(layers);
	for(var i=0; i<layers.length; i++){
			removeMapLayer(layers[i]);
			layers.splice(i,1);
	}
}


function getBaseURL() {
	var url = location.href;
	var baseURL = url.substring(0, url.indexOf('/', 14));

	if (baseURL.indexOf('http://localhost') != -1) {
		var url = location.href;
		var pathname = location.pathname;
		var index1 = url.indexOf(pathname);
		var index2 = url.indexOf("/", index1 + 1);
		var baseLocalUrl = url.substr(0, index2);

		return baseLocalUrl + "/";
	} else {
		// Root Url for domain name
		return baseURL + "/";
	}
}
