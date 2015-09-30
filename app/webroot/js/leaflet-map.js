var geoserverUrl = 'http://localhost:8080/geoserver/cite/wms?';
var mapToken = 'pk.eyJ1IjoiZmVlZC1maW5kZXIiLCJhIjoiMDIyMGI4ZmU4ZmFlYTMxMDFlMjYyZmJmNzQ5OWJhOGEifQ.6cOhRAs3U0blI_n-cJxD0g';
var sidebar;
var uri;
var legend,currentOverlay;
$(document).ready(function() {

 	var street =	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
		maxZoom: 18,
		id: 'davidoyeku.n73bd296',
		accessToken: mapToken
	});

	map = L.map('map',{
      center: [51.505, -0.09],
      zoom: 5,
      layers: [street]
    });

	map.on('layeradd', overlayAdd);
	map.on('overlayadd', overlayAdd);
	map.on('overlayremove',overlayRemove);
	var lc = L.control.locate({
		position:'topright',
		 icon: 'fa fa-location-arrow',
       locateOptions: {
               maxZoom:15
}}).addTo(map);

//lc.start();

	geocoder = L.Control.geocoder().addTo(map);
	sidebar = L.control.sidebar('sidebar');
	sidebar.addTo(map);

	customMarker = L.Marker.extend({
   options: {
   }
});




});
function overlayAdd(e){
currentOverlay = e.layer;
}

function overlayRemove(e){
	currentOverlay = null;
}









function removeMapLayer(layer) {
	if (map.hasLayer(layer)) {
		map.removeLayer(layer);
	}
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
