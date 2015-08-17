var map, world, adminOne, adminThree;
var simpCounter = 0;
var geoserverUrl = 'http://localhost:8080/geoserver/cite/wms';
var mapToken= 'pk.eyJ1IjoiZmVlZC1maW5kZXIiLCJhIjoiMDIyMGI4ZmU4ZmFlYTMxMDFlMjYyZmJmNzQ5OWJhOGEifQ.6cOhRAs3U0blI_n-cJxD0g';

$(document).ready(function() {

	getColorRangeWorld();
	getColorRangeAdminOne();
	getColorRangeAdminThree();

	L.mapbox.accessToken = mapToken;
	map = L.mapbox.map('map', 'mapbox.streets')
	.setView([37.8, -96], 2);

	mapZoomed();

});

function getColorRangeWorld(){

	$.ajax
	({
		dataType: 'json',
		type:'GET',
		data:{model:'World'},
		url: getBaseURL() + '/feed_finder_transactions/' + 'world_review_range',
		success: function(data) {
			getWmsTiles(function(layer) { world = layer; world.addTo(map); },
											data,
											'worlds',
											true);
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
			getWmsTiles(function(layer) { adminOne = layer },
									data,
									'admin_ones',
									false);
		}
	});

}

function getColorRangeAdminThree(){

	$.ajax
	({
		dataType: 'json',
		type:'GET',
		data:{model:'UkAdminThree'},
		url: getBaseURL() + '/feed_finder_transactions/' + 'world_review_range',
		success: function(data) {
			getWmsTiles(function(layer) { adminThree = layer;},
									data,
									'uk_admin_threes',
									 false);
		}
	});

}

function getWmsTiles(mapboxLayer, data, geoserverLayer, addToMap){
	var first_q = data.first_q;
	var second_q = data.second_q;
	var third_q = data.third_q;

  mapboxLayer(L.tileLayer.wms
	(geoserverUrl+'?SERVICE=WMS&REQUEST=GetMap&env=first_q:'+first_q+';second_q:'+second_q+';third_q:'+third_q+';&VERSION=1.1.0', {
			layers: 'cite:'+geoserverLayer,
			format: 'image/png',
			transparent: true,
			version: '1.1.0',
			tiled :true,
			attribution: "myattribution",
	})
 );




	}

function currentLayer(){
	if(map.hasLayer(world)){
		return world;
	}else if (map.hasLayer(adminOne)){
		return adminOne;
	}else if (map.hasLayer(adminThree)){
		return adminThree;
	}
}



function mapZoomed(){
	map.on('zoomend', function(e) {
		console.log(map.getZoom());
	if (map.getZoom() >= 6 && map.getZoom() <= 10) {
		if (simpCounter == 0 || simpCounter == 2) {
			if(map.hasLayer(world)){
					map.removeLayer(world);
			}
			if(map.hasLayer(adminThree)){
					map.removeLayer(adminThree);
			}
			adminOne.addTo(map);
			simpCounter = 1;
		}
	} else if (map.getZoom() >= 11) {
		if (simpCounter == 0 || simpCounter == 1) {
		simpCounter = 2;
				if(map.hasLayer(adminOne)){
					map.removeLayer(adminOne);
				}
				adminThree.addTo(map)
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
