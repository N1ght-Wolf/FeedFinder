var map, geoLayerW, geoLayerC;
var countryFeatureIndex = {};
var addedToGeoLayerC = [];
var simpCounter = 0;
$(document).ready(function() {

	fetchWorldReviews('world_reviews');
	initWorldJson();

	map = L.map('map').setView([51.505, -0.09], 3);
	L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

	geoLayerW = L.geoJson(world, {
		onEachFeature: onEachFeatureW,
		style: style,
		filter: function(feature, layer) {
			return feature.properties.show_on_map;
		}
	}).addTo(map);

	geoLayerC = L.geoJson(gbr, {
		onEachFeature: onEachFeatureC,
		style: style
	});

	map.on('zoomend', function(e) {
		if (map.getZoom() <= 1) {
			geoLayerC.clearLayers();
			map.removeLayer(geoLayerC);
			setAllShowOnMap(world, true);
			map.removeLayer(geoLayerW);
			console.log(world);
			geoLayerW.addTo(map);
		}
		console.log(map.getZoom());
	});

});

function zoomToFeature(e) {
	map.fitBounds(e.target.getBounds());
  console.log(e.target.getBounds());
}

function zoomChangeLayer(e) {
	console.log(e);
	var result = leafletPip.pointInLayer([e.latlng.lng, e.latlng.lat], geoLayerW);
	var countryId;
	console.log(result);
	if (result.length > 0 && addedToGeoLayerC.indexOf(result[0].feature.id) < 0) {
		countryId = result[0].feature.id;
		result[0].feature.properties.show_on_map = false;
		geoLayerW.clearLayers();
		geoLayerW.addData(world);

		$.getScript("../js/" + countryId + ".js", function() {
			geoLayerC.addData(eval(countryId.toLowerCase())).addTo(map);
			addedToGeoLayerC.push(countryId); //remember what has been added to geoLayerC
		});
    var bounds = e.target.getBounds();
    fetchCountryReviews(bounds);
    console.log(bounds);
	}

function zoomOutChangeLayer(e){

}

	map.fitBounds(e.target.getBounds());
}

function filter() {

}

function getColor(d) {

	return d > 1000 ? '#800026' :
		d > 500 ? '#BD0026' :
		d > 40 ? '#E31A1C' :
		d > 30 ? '#FC4E2A' :
		d > 10 ? '#FD8D3C' :
		d > 5 ? '#FEB24C' :
		d > 2 ? '#FED976' :
		'GREEN';
}

function style(feature) {
	return {
		fillColor: getColor(feature.properties.reviewCount),
		weight: 1,
		opacity: 1,
		color: 'white',
		dashArray: '2',
		fillOpacity: 0.5
	};
}

function onEachFeatureW(feature, layer) {
	layer.on({
		mouseover: highlightFeature,
		mouseout: resetHighlight,
		click: zoomToFeature,
		dblclick: zoomChangeLayer
	});
}

function onEachFeatureC(feature, layer) {
	layer.on({
		mouseover: highlightFeature,
		mouseout: resetHighlight,

	});
}

function highlightFeature(e) {
	//hover style
	var layer = e.target;

	layer.setStyle({
		weight: 5,
		color: '#666',
		dashArray: '',
		fillOpacity: 0.7
	});

	if (!L.Browser.ie && !L.Browser.opera) {
		layer.bringToFront();
	}
}

function resetHighlight(e) {
	//mouse out style
	geoLayerW.resetStyle(e.target);
	// geoLayerC.resetStyle(e.target);

}

function fetchWorldReviews() {
	$.ajax({
		type: 'GET',
		dataType: "json",
		data: form_data,
		url: getBaseURL() + '/feed_finder_transactions/' + 'world_reviews',
		success: function(data) {
			console.log(data);
			var features = world.features;
			var lat, lng, reviewCount, results, index;
			//merge the database count value with the rightful features
			//e.g. GBR = 1958
			for (var i = 0; i < data.length; i++) {
				lat = data[i].Venue.lat;
				lng = data[i].Venue.lng;
				reviewCount = data[i][0].mycount;
				results = leafletPip.pointInLayer([lng, lat], geoLayerW);
				if (results.length > 0) {
					index = countryFeatureIndex[results[0].feature.id];
					features[index].properties.reviewCount += parseInt(data[i][0].mycount);
				}
			}
			console.log(world);
			geoLayerW.setStyle(style);

		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	});
}

function fetchCountryReviews(bounds){
  $.ajax({
    type: 'POST',
    url: getBaseURL() + '/feed_finder_transactions/' + 'bounding_box',
    dataType: 'json',
    data: {north_lat:bounds._northEast.lat,
           south_lat:bounds._southWest.lat,
           east_lng:bounds._northEast.lng,
           west_lng: bounds._southWest.lng},
    success: function(data){
      alert('success');
      console.log(data);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}

function initWorldJson() {
	var features = world.features;
	for (var i = 0; i < features.length; i++) {
		countryId = features[i].id;
		//also store their index for easier access
		countryFeatureIndex[countryId] = i;
		features[i].properties.reviewCount = 0;
		features[i].properties.show_on_map = true;
	}
}

function setAllShowOnMap(gjson, bool) {
	var features = gjson.features;
	for (var i = 0; i < features.length; i++) {
		features[i].properties.show_on_map = bool;
	}
}

function typeOf(obj) {
	return {}.toString.call(obj).split(' ')[1].slice(0, -1).toLowerCase();
}
