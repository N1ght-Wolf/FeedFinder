var heatMap, pointArray;
var map;
var defaultLng, defaultLat;

$(document).ready(function(){

  //Start geolocation
  getUserLocation();



});

function getUserLocation(){
  if (navigator.geolocation) {

    function error(err) {
      console.warn('ERROR(' + err.code + '): ' + err.message);
    }

    function success(pos){
      // console.log(pos.coords);
      // reverseGeocode(pos.coords);
      fetchRelevantData(pos);
    //  initialize(pos.coords.latitude,pos.coords.longitude);
    }

    // Get the user's current position
    navigator.geolocation.getCurrentPosition(success, error);
    //console.log(pos.latitude + " " + pos.longitude);
    } else {
      alert('Geolocation is not supported in your browser');
    }

  //End Geo location
}

// function reverseGeocode(coordinate){
//   lat = coordinate.latitude;
//   long = coordinate.longitude;
//   $.ajax({ url:'http://maps.googleapis.com/maps/api/geocode/json?latlng='+lat+','+long+'&sensor=true',
//          success: function(data){
//               console.log(data);
//               fetchRelevantData(data);
//          }
//        });
// }

function initialize(data) {

 var markerLatLngArray = [];
 $.each(data, function(key, value) {
   var floatLat = parseFloat(value.FeedFinderTransaction.lat);
   var floatLng = parseFloat(value.FeedFinderTransaction.lng);
   var latlng =  new google.maps.LatLng(floatLat, floatLng);
   markerLatLngArray.push(latlng);

 });

 var mapProp = {
   center:new google.maps.LatLng(defaultLat,defaultLng),
   zoom:10,
   mapTypeId:google.maps.MapTypeId.ROADMAP
 };
 map=new google.maps.Map(document.getElementById("geo_div"),mapProp);


  var pointArray = new google.maps.MVCArray(markerLatLngArray);
  console.log(markerLatLngArray);
  heatMap = new google.maps.visualization.HeatmapLayer({
   data: pointArray
 });
 heatMap.setMap(map);
}


function fetchRelevantData(data){
  defaultLat = data.coords.latitude;
  defaultLng = data.coords.longitude;
  $.ajax({
    type:'POST',
    data:{lat: data.coords.latitude,lng: data.coords.longitude},
    dataType:'json',
    url: getBaseURL() + '/feed_finder_transactions/' +'fetchRelevantData',
    success: function(data){

      initialize(data);
      // console.log(data[0].FeedFinderTransaction.lat);
    },
    error: function(xhs,textStatus,error){
      console.log(textStatus);
    }

  });
}
function changeRadius() {
  heatmap.set('radius', heatmap.get('radius') ? null : 10);
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
