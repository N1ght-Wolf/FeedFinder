var categories, times, explore,query;

$(document).ready(function() {
	var sidebar = $('#sidebar').sidebar().open('home');
});

feedfinder.controller('sidebarSelectController',function($scope, $http){
	$scope.loading=false;
	$scope.categories = [
	{name: 'User', model: 'User'},
	{name: 'Venue', model: 'Venue'},
	{name: 'Review', model: 'Review'},
	{name: 'Friendliness', model: 'Review'}
	];

	$scope.times = [
	{name: timeArr[0], range:getDateRange(timeArr[0]), attr_name:'_today'}, //today
	//{name: timeArr[1], range:getDateRange(timeArr[1])}, //yesterday
	{name: timeArr[2], range:getDateRange(timeArr[2]), attr_name:'_this_week'}, //this week
	//{name: timeArr[3], range:getDateRange(timeArr[3])}, //last week
	{name: timeArr[4], range:getDateRange(timeArr[4]), attr_name:'_this_month'}, //this month
	//{name: timeArr[5], range:getDateRange(timeArr[5])}, //last month
	{name: timeArr[6], range:getDateRange(timeArr[6]), attr_name:'_three_month'}, //last 3 months
	{name: timeArr[7], range:getDateRange(timeArr[7]), attr_name:'_six_month'}, //last 6 months
	{name: timeArr[8], range:getDateRange(timeArr[8]), attr_name:'_this_year'}, //this year
	{name: timeArr[9], range:getDateRange(timeArr[9]), attr_name:'_all'} //all
	];

	$scope.explore = [
	{name: 'County', groupBy:'Venue.county_id',pg_table:'County'},
	{name: 'Super Output Area (UK)', groupBy:'Venue.soa_id',pg_table:'Soa'}
	];

	$scope.selectedTime = 	{name: timeArr[7], range:getDateRange(timeArr[7]), attr_name:'_six_month'};
	$scope.selectedCategory = 	{name: 'Review', model: 'Review'};
	$scope.selectedExplore = {name: 'County', groupBy:'Venue.county_id',pg_table:'County'};

		/*watch all of the select fields, when they change make an ajax request
		[categories,times,explore]
		*/
	$scope.$watchCollection('[selectedCategory.name, selectedTime.name, selectedExplore.name]', function(newValues){
	//delete all the markers
	deleteMarkers();
	//remove the choropleth map overlay
	if(map !=null){
		map.overlayMapTypes.clear();
	}
	var selectedCategory = search(newValues[0], $scope.categories);
	var selectedTime = search(newValues[1], $scope.times);		
	var selectedExplore = search(newValues[2], $scope.explore);

	query = {
		category:selectedCategory,
		time:selectedTime,
		explore:selectedExplore
	}
	$.ajax({
		type: 'GET',
		dataType: 'json',
		url: url()+'/map_query',
		data:query,
		beforeSend: function(){
		},
		success: function (result){
			console.log(result);
			queryCallBack(result);
		},
		error: function (jqXHR, textStatus, errorThrown) {
		}
	});
});

	});

function queryCallBack(result){
	var name = result.request.category.name;
	switch (name){
		case 'Venue':
		displayMarkers(result.result.time_range);
		getChoroplethMap(result.result.interq);
		break;
		default:
		console.log(result.result);
		getChoroplethMap(result.result);
		break;

	}
}

function getChoroplethMap(interq){
	var quartiles = interq.quartiles;
	console.log(quartiles);
	choroplethMap = new google.maps.ImageMapType({
		getTileUrl: function (coord, zoom) {
			var proj = map.getProjection();
			var zfactor = Math.pow(2, zoom);
                        // get Long Lat coordinates
                        var top = proj.fromPointToLatLng(new google.maps.Point(coord.x * 256 / zfactor, coord.y * 256 / zfactor));
                        var bot = proj.fromPointToLatLng(new google.maps.Point((coord.x + 1) * 256 / zfactor, (coord.y + 1) * 256 / zfactor));

                        //corrections for the slight shift of the SLP (mapserver)
                        var deltaX = 0.0013;
                        var deltaY = 0.00058;
                        //create the Bounding box string
                        var bbox =     (top.lng() + deltaX) + "," +
                        (bot.lat() + deltaY) + "," +
                        (bot.lng() + deltaX) + "," +
                        (top.lat() + deltaY);
                        //base WMS URL
                        geoserverUrl = "http://localhost:8080/geoserver/cite/wms?";
                        geoserverUrl +='&env=first_q:'+quartiles[1]+
						';second_q:'+quartiles[2]+';third_q:'+quartiles[3]+';fourth_q:'+quartiles[4]+';fifth_q:'+quartiles[5];
                        geoserverUrl += "&REQUEST=GetMap";
                        geoserverUrl += "&SERVICE=WMS";    //WMS service
                        geoserverUrl += "&VERSION=1.1.1";  //WMS version  
						geoserverUrl += "&STYLES="+interq.style;//WMS version  
                        geoserverUrl += "&LAYERS=" + "cite:"+interq.layer; //WMS layers
                        geoserverUrl += "&FORMAT=image/png" ; //WMS format
                        geoserverUrl += "&TRANSPARENT=TRUE";
                        geoserverUrl += "&SRS=EPSG:4326";     //set WGS84 
                        geoserverUrl += "&BBOX=" + bbox;      // set bounding box
                        geoserverUrl += "&WIDTH=256";         //tile size in google
                        geoserverUrl += "&HEIGHT=256"

                        return geoserverUrl;                 // return URL for the tile

                    },
                    tileSize: new google.maps.Size(256, 256),
                    isPng: true
                });
	map.overlayMapTypes.push(choroplethMap);
	console.log(choroplethMap);
}

/*
Loop over an array of objects and return the object that contains the nameKey
*/
function search(nameKey, myArray){
	for (var i=0; i < myArray.length; i++) {
		if (myArray[i].name === nameKey) {
			return myArray[i];
		}
	}
}