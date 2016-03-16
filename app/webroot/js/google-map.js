var map;

$(document).ready(function() {

});

function initMap() {
	var map = new google.maps.Map(document.getElementById("map"), {
	center: new google.maps.LatLng(51.2, 7),
	zoom: 5,
	mapTypeId: google.maps.MapTypeId.TERRAIN,
});
}
