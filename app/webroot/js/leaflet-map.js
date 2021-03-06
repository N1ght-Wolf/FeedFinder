var geoserverUrl = 'http://localhost:8080/geoserver/cite/wms?';
var mapToken = 'pk.eyJ1IjoiZmVlZC1maW5kZXIiLCJhIjoiMDIyMGI4ZmU4ZmFlYTMxMDFlMjYyZmJmNzQ5OWJhOGEifQ.6cOhRAs3U0blI_n-cJxD0g';
var sidebar;
var uri;
var legend, currentOverlay;
var layerGroup;
var clickLocation;
var dialog;
$(document).ready(function () {

    var street = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    });

    map = L.map('map', {
        center: [51.505, -0.09],
        zoom: 5,
        zoomControl:false,
        layers: [street],
        inertiaMaxSpeed: 800
    });

    L.control.zoom({
        position:'topright'
    }).addTo(map);
    dialog = L.control.dialog(options).addTo(map);

    map.on('layeradd', overlayAdd);
    map.on('overlayadd', overlayAdd);
    map.on('overlayremove', overlayRemove);
    map.on('click', function(e) {
        clickLocation = {lat:e.latlng.lat,lng:e.latlng.lng,from:currentDateSelection.from,to:currentDateSelection.to};
                       $.ajax({
                type: 'GET',
                dataType: 'json',
                url: $.ajaxSettings.url + 'click_location',
                data: clickLocation,
                success: function (data) {
                    dialog.setContent(
                        "<h3>Review  "+data.reviews+"</h3>"+
                        "<br><h3>Venues  "+data.venues+"</h3>"+
                        "<br><h3>Friendliness  "+data.friendliness+"</h3>");
                    dialog.open();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                  

                }
            });


    // alert("Lat, Lon : " + e.latlng.lat + ", " + e.latlng.lng)
});

    var lc = L.control.locate({
        position: 'topright',
        icon: 'fa fa-location-arrow',
        locateOptions: {
            maxZoom: 15
        }
    }).addTo(map);

    geocoder = L.Control.geocoder({
        placeholder : "enter postcode, county or country"
    }).addTo(map);

    sidebarControl = L.control.sidebar('sidebar');
    sidebarControl.addTo(map);

    layerGroup = new L.LayerGroup().addTo(map);

});
function overlayAdd(e) {
    currentOverlay = e.layer;
}

function overlayRemove(e) {
    currentOverlay = null;
}

function identify(e) {
   // set parameters needed for GetFeatureInfo WMS request
   var BBOX = map.getBounds().toBBoxString();
   var WIDTH = map.getSize().x;
   var HEIGHT = map.getSize().y;
   var X = map.layerPointToContainerPoint(e.layerPoint).x;
   var Y = map.layerPointToContainerPoint(e.layerPoint).y;
   // compose the URL for the request
   var URL =
       geoserverUrl +
       'request=GetFeatureInfo' +
       '&SERVICE=WMS' +
       '&VERSION=1.1.1&LAYERS=cite:worlds&query_layers=cite:worlds&STYLES=review_sld_style&FORMAT=image%2Fpng8&TRANSPARENT=true&HEIGHT=' + HEIGHT + '&WIDTH=' + WIDTH + '&BBOX=' + BBOX + '&X=' + X + '&Y=' + Y;

   //send the asynchronous HTTP request using jQuery $.ajax
   $.ajax({
       url: URL,
       dataType: "json",
       type: "GET",
       success: function (data) {
           var popup = new L.Popup
           ({
               maxWidth: 300
           });

           popup.setContent(data);
           popup.setLatLng(e.latlng);
           map.openPopup(popup);
       }
   });
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
