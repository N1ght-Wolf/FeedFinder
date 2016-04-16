var map;
var markers = [];
var markerCluster;
var geoserverUrl = 'http://localhost:8080/geoserver/cite/wms?';
var choroplethMap;
var layerMarker;


$(document).ready(function() {
	// console.log(url(1));
	console.log(url('hostname')+'/'+url(1)+'/venues');	
});

function initMap() {
	map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(54.2, -1.7),
		zoom: 6,
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.TOP_RIGHT
		},
		    disableDefaultUI: true,
		mapTypeId: google.maps.MapTypeId.TERRAIN,
	});

	map.addListener('click', function(event){
		event.latLng.lat();
		if(layerMarker != null){
			layerMarker.setMap(null);
		}
		var pg_column = query.category.name.toLowerCase()+query.time.attr_name;
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: url()+'/map_click',
			data:{
				latitude:event.latLng.lat(),
				longitude:event.latLng.lng(),
				model:query.explore.pg_table,
				pg_column:pg_column
			},
			success: function (result){
				console.log(result);
				var obj = result[Object.keys(result)[0]];
				var count = obj[Object.keys(obj)[0]];
				var infoWindow = new google.maps.InfoWindow({
					content: count+" "+query.category.name+'s'
				});

			var image = {
			    url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
			    // This marker is 20 pixels wide by 32 pixels high.
			    size: new google.maps.Size(20, 32),
			    // The origin for this image is (0, 0).
			    origin: new google.maps.Point(0, 0),
			    // The anchor for this image is the base of the flagpole at (0, 32).
			    anchor: new google.maps.Point(0, 32)
			  };

				layerMarker = new google.maps.Marker({
					position: new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()),
					map: map,
					icon: image,
					animation: google.maps.Animation.DROP,
				});
				layerMarker.addListener('click', function() {
					infoWindow.open(map, layerMarker);
				});    
			},
			error: function (jqXHR, textStatus, errorThrown) {
			}
		});
	});
}

function displayMarkers(venueResult){
	var venue, review, infoText, latLng, marker;
	var infoWindow = new google.maps.InfoWindow();
	venueResult = removeNullAttributes(venueResult);
	for (var i = 0; i < venueResult.length; i++) {
		//check if there are any venues in the result
		venue = venueResult[i]['Venue'];
		infoText = generateHtmlMarkup(venue);
		infoText += "<a target='_blank'href=venues/?id="+venue.id+">Visit</a>";
		review = venueResult[i]['Review'];
		latLng = new google.maps.LatLng(venue.latitude,venue.longitude);
		marker = new google.maps.Marker({'position': latLng,map:map});
		delete venue.latitude;
		google.maps.event.addListener(marker, 'click', (function(marker, infoText) {
			return function() {
				infoWindow.setContent(infoText);
				infoWindow.open(map, marker);
			}
		})(marker, infoText));
		markers.push(marker);
	}
	markerCluster = new MarkerClusterer(map, markers);
}

function deleteMarkers() {
	if(markers.length > 0){
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		}
		markers = [];
		markerCluster.clearMarkers();	
	}

}

function generateHtmlMarkup(venue){
	var infoString = "";
	venue = removeNullAttributes(venue);
	for(var i in venue){
		infoString  = infoString+venue[i]+"</br>"
	}
	return infoString;
}


/*
	Loop through each of the attributes and remove the
	ones with null values
	*/
	function removeNullAttributes(venue){
		for(var i in venue){
			if( venue[i] === null || venue[i] === undefined ){
				delete venue[i];
			}
		}
		return venue;
	}


