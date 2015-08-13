var map, world, adminOne;
var colorRange;
var simpCounter = 0;
var geoserverUrl = 'http://localhost:8080/geoserver/cite/wms';
var mapToken= 'pk.eyJ1IjoiZmVlZC1maW5kZXIiLCJhIjoiMDIyMGI4ZmU4ZmFlYTMxMDFlMjYyZmJmNzQ5OWJhOGEifQ.6cOhRAs3U0blI_n-cJxD0g';
var legend;
$(document).ready(function() {
	legend = L.control({position: 'bottomright'});

	getColorRangeWorld();
	getColorRangeAdminOne();

	L.mapbox.accessToken = mapToken;
	map = L.mapbox.map('map', 'mapbox.streets')
	  .setView([37.8, -96], 2);



	map.on('zoomend', function(e) {
	if (map.getZoom() >= 6 && map.getZoom() <= 10) {
		if (simpCounter == 0 || simpCounter == 2) {
			if(map.hasLayer(world)){
					map.removeLayer(world);
			}
			adminOne.addTo(map);
			simpCounter = 1;
		}
	} else if (map.getZoom() >= 11) {
		if (simpCounter == 0 || simpCounter == 1) {
		simpCounter = 2;
		}
	} else if (map.getZoom() <= 6) { //Return to original data
		if (simpCounter == 1 || simpCounter == 2) {
			if(map.hasLayer(adminOne)){
				map.removeLayer(adminOne);
			}
			world.addTo(map);
		simpCounter = 0;
		}
	}
	});



});

function getColorRangeWorld(){

	$.ajax
	({
		dataType: 'json',
		type:'GET',
		data:{model:'World'},
		url: getBaseURL() + '/feed_finder_transactions/' + 'world_review_range',
		success: function(data) {
			getWorldWmsTiles(data.first_q,
									data.second_q,
									data.third_q,
									data.max);
		}
	});

}
function getColorRangeAdminOne(){

	$.ajax
	({
		dataType: 'json',
		type:'GET',
		data:{model:'AdminOne'},
		url: getBaseURL() + '/feed_finder_transactions/' + 'world_review_range',
		success: function(data) {
			getAdminWmsTiles(data.first_q,
									data.second_q,
									data.third_q,
									data.max);
		}
	});

}

function getWorldWmsTiles(first_q, second_q, third_q, max){
	world = L.tileLayer.wms
	(geoserverUrl+'?SERVICE=WMS&REQUEST=GetMap&env=first_q:'+first_q+';second_q:'+second_q+';third_q:'+third_q+';max:'+max+';&VERSION=1.1.0', {
			layers: 'cite:worlds',
			format: 'image/png',
			transparent: true,
			version: '1.1.0',
			attribution: "myattribution"
	});
	world.addTo(map);
	legend.onAdd = function (map) {
var div = L.DomUtil.create('div', 'info legend');

    div.innerHTML +=
    '<img src="http://localhost:8080/geoserver/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=cite:worlds" alt="legend" width="134" height="147">';
		legend.addTo(map);

};

	}

function getAdminWmsTiles(first_q, second_q, third_q, max){

	adminOne = L.tileLayer.wms
	(geoserverUrl+'?SERVICE=WMS&REQUEST=GetMap&env=first_q:'+first_q+';second_q:'+second_q+';third_q:'+third_q+';max:'+max+';&VERSION=1.1.0', {
			layers: 'cite:admin_ones',
			format: 'image/png',
			transparent: true,
			version: '1.1.0',
			attribution: "myattribution"
	});

}

//functions
function getColor(d) {
    return d > 500 ? '#800026' :
           d > 250 ? '#BD0026' :
           d > 100 ? '#E31A1C' :
           d > 50  ? '#FC4E2A' :
           d > 20  ? '#FD8D3C' :
           d > 10  ? '#FEB24C' :
           d > 3   ? '#FED976' :
                     'GREEN';
}

function style(feature) {
    return {
        fillColor: getColor(feature.properties.review),
        weight: 2,
        opacity: 1,
        color: 'white',
        dashArray: '3',
        fillOpacity: 0.7
    };
}

function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        weight: 5,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
    });

    if(!L.Browser.ie && !L.Browser.opera){
        layer.bringToFront();
    }
}

function resetHighlight(e) {
    worldLayer.resetStyle(e.target);
}
function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
		var iso3 = e.target.feature.properties.iso3;
		//get its index
		index = worldIndex[iso3];
		//change its visibility property
		worldGeoJson.features[index].properties.show = false;
		//clear and update the layer
		worldLayer.clearLayers();
		worldLayer.addData(worldGeoJson);
		getCountryGeojson(iso3.toLowerCase()+'s');
}
function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        // dblclick: zoomToFeature,
    });
}

function filter(feature, layer) {
		 return feature.properties.show;
 }



function getCountryGeojson(iso){
	//se the layer and callback method to use
	layerFromGeoserver = 'cite:'+iso;
	callbackMethod='callbackCountry';
	getGeoserverGeojson(layerFromGeoserver,
											callbackMethod);

}


function readyWorldReview(){
	$.ajax({
		url: getBaseURL() + '/feed_finder_transactions/' + 'world_reviews',
	  success: function(data) {
			//console.log(data);
			getGeoserverGeojson('cite:worlds',
													'callbackWorld'
												);
	  }
	});
}

function readyCountryReview(boundingBox, iso3){
	var north_lat = boundingBox['_northEast']['lat'];
	var north_lng = boundingBox['_northEast']['lng'];
	var south_lat = boundingBox['_southWest']['lat'];
	var south_lng = boundingBox['_southWest']['lng'];
	console.log(iso3);
	$.ajax({
		type:'POST',
		url: getBaseURL() + '/feed_finder_transactions/' + 'country_reviews',
		data:{north_lng:north_lng, north_lat:north_lat,
					south_lng:south_lng, south_lat:south_lat,
					iso3:iso3
				},
		success: function(data) {
			console.log(data);
			getGeoserverGeojson('cite:'+iso3.toLowerCase()+'s',
													'callbackCountry'
												);
		}
	});
}

function getGeoserverGeojson(layer, callback){
	//set the query for the url
	var query = setGeoserverRequest(layer,
		 															callback);

	var parameters = L.Util.extend(query);
	//make the request from geoserver
	$.ajax(geoserverUrl+L.Util.getParamString(parameters),
	{ dataType: 'jsonp' }
	).done(function ( data ) {
	});
}

function setGeoserverRequest(typeName, callback){
	var defaultParameters = {
		service: 'WFS',
		version: '1.0.0',
		request: 'GetFeature',
		typeName: typeName,
		maxFeatures: 100000,
		outputFormat: 'text/javascript',
		format_options: 'callback:'+callback
	};
	return defaultParameters;
}

function callbackWorld(mapData) {
	setWorldLayer(mapData);
}


function callbackCountry(data){
	//console.log(data);
	countryLayer.addData(data);
}


function setWorldLayer(data){
	if(isEmpty(worldIndex)){
		//make indexs for each country
		indexDataWorld(data, worldIndex);
	}
	//keep copy of geoJson data
	worldGeoJson = data;
	//make a layer defining
	//style, filter etc.
	worldLayer = L.geoJson(data,{
		style:style,
		onEachFeature: onEachFeature,
		filter:filter
	}).addTo(map);

}

function setCountryLayer(data){
	if(isEmpty(countryIndex)){
		//make indexs for each country
		indexDataCountry(data,countryIndex);
	}
	console.log(countryIndex);

	//keep copy of geoJson data
	// worldGeoJson = data;
	//make a layer defining
	//style, filter etc.
	countryLayer = L.geoJson(data,{
		style:style,
		onEachFeature: onEachFeature,
		filter:filter
	}).addTo(map);
	console.log(countryLayer);

}


function indexDataWorld(data, indexObj){
	//index the countrys by iso for easier future lookups
	for( var i=0 ; i < data.totalFeatures; i++){
			indexObj[data.features[i].properties.iso3] = i;
	}
}

function indexDataCountry(data, indexObj){
	//index the countrys by iso for easier future lookups
	for( var i=0 ; i < data.totalFeatures; i++){
			indexObj[data.features[i].properties.name_2] = i;
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

function isEmpty(obj) {

    // null and undefined are "empty"
    if (obj == null) return true;

    // Assume if it has a length property with a non-zero value
    // that that property is correct.
    if (obj.length > 0)    return false;
    if (obj.length === 0)  return true;

    // Otherwise, does it have any properties of its own?
    // Note that this doesn't handle
    // toString and valueOf enumeration bugs in IE < 9
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }

    return true;
}
