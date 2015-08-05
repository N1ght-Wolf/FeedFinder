var map;
var geoserverUrl = 'http://localhost:8080/geoserver/cite/ows';
var worldLayer, worldGeoJson;
var worldIndex = {};
var countryLayer, countryGeoJson;
var countryIndex = {};
var layerFromGeoserver, callbackMethod;
var mapToken= 'pk.eyJ1IjoiZmVlZC1maW5kZXIiLCJhIjoiMDIyMGI4ZmU4ZmFlYTMxMDFlMjYyZmJmNzQ5OWJhOGEifQ.6cOhRAs3U0blI_n-cJxD0g';

$(document).ready(function() {
	//first layer for map is world
	layerFromGeoserver = 'cite:worlds';
	callbackMethod = 'callbackWorld'
	//update the data is PostgreSQL for world country
	readyWorldReview();
	//instantiate a new mapbox
	L.mapbox.accessToken = mapToken;
	map = L.mapbox.map('map', 'mapbox.streets')
	  .setView([37.8, -96], 2);

});

//functions
function getColor(d) {
    return d > 500 ? '#800026' :
           d > 250  ? '#BD0026' :
           d > 100  ? '#E31A1C' :
           d > 50  ? '#FC4E2A' :
           d > 20   ? '#FD8D3C' :
           d > 10   ? '#FEB24C' :
           d > 5   ? '#FED976' :
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
		//find the country iso
		var iso3 = e.target.feature.properties.iso3;
		//get its index
		index = worldIndex[iso3];
		//change its visibility property
		worldGeoJson.features[index].properties.show = false;
		//clear and update the layer
		worldLayer.clearLayers();
		worldLayer.addData(worldGeoJson);
		getCountryGeojson();
}
function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        dblclick: zoomToFeature,
    });
}

function filter(feature, layer) {
		 return feature.properties.show;
 }

function callbackWorld(mapData) {
	setWorldLayer(mapData);
}
function setWorldLayer(data){
	if(isEmpty(worldIndex)){
		//make indexs for each country
		indexData(data, worldIndex);
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

function indexData(data, indexObj){
	//index the countrys by iso for easier future lookups
	for( var i=0 ; i < data.totalFeatures; i++){
			indexObj[data.features[i].properties.iso3] = i;
	}
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

function getCountryGeojson(){
	//se the layer and callback method to use
	layerFromGeoserver = 'cite:gbrs';
	callbackMethod='callbackCountry';
	getGeoserverGeojson(layerFromGeoserver,
											callbackMethod);

}

function callbackCountry(data){
	console.log(data);
	setCountryLayer(data);
}
function setCountryLayer(data){
	if(isEmpty(countryIndex)){
		//make indexs for each country
		indexData(data,countryIndex);
	}

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

function readyWorldReview(){
	$.ajax({
		url: getBaseURL() + '/feed_finder_transactions/' + 'world_reviews',
	  success: function(data) {
			console.log(data);
			getGeoserverGeojson(layerFromGeoserver,
													callbackMethod
												);
	  }
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
