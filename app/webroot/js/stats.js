var peopleForm, placesForm, reviewForm, friendlinessForm;
var peopleTimeSelect, placesTimeSelect, reviewTimeSelect, friendlinessTimeSelect;
var peopleHeatMapSelect, placesHeatMapSelect, reviewHeatMapSelect, friendlinessHeatMapSelect;
var markers;
var mapQuery = {
    from: 0,
    to: 0
};
//options for the styled layer control toggle
var options = {
    container_width: "300px",
    group_maxHeight: "80px",
    exclusive: false,
    collapsed: false
};
//setting the default url for the ajax request
$.ajaxSetup({
    url: getBaseURL() + 'dashboards/',
});

$(document).ready(function () {
    //new marker cluster to be reused
    markers = new L.MarkerClusterGroup();

    peopleForm = $('#people-map-form');
    placesForm = $('#places-map-form');
    reviewForm = $('#review-map-form');
    friendlinessForm = $('#friendliness-map-form');

    //find the timespan for each tab
    peopleTimeSelect = $(peopleForm).find('select').eq(0);
    placesTimeSelect = $(placesForm).find('select').eq(0);
    reviewTimeSelect = $(reviewForm).find('select').eq(0);
    friendlinessTimeSelect = $(friendlinessForm).find('select').eq(0);
    //find the heatmap select for each tab
    peopleHeatMapSelect = $(peopleForm).find('select').eq(1);
    placesHeatMapSelect = $(placesForm).find('select').eq(1);
    reviewHeatMapSelect = $(reviewForm).find('select').eq(1);
    friendlinessHeatMapSelect = $(friendlinessForm).find('select').eq(1);

    //open the  control sidebarControl when loaded
    sidebarControl.open('people');
    //set the default selected item to 6th element (3months)
    $(placesTimeSelect).prop('selectedIndex', 6);
    $(peopleTimeSelect).prop('selectedIndex', 6);


    //put each tabs selectors in an array
    var selectors = [
        peopleTimeSelect, placesTimeSelect, reviewTimeSelect, friendlinessTimeSelect,
        peopleHeatMapSelect, reviewHeatMapSelect, friendlinessHeatMapSelect
    ];


    //when any of the selector in the 'selectors' array are changed do the following
    $(selectors).each(function () {
        $(this).change(function () {
            //if a date select has been changed
            if ($(this).hasClass('time-select')) {
                console.log(sidebarControl.getActiveTab() + ' time select changed');
                updateMapQuery(this);
                submitMapQuery();
            }// if the type of heatmap layer has been changed
            else {
                submitMapQuery();
            }
        });
    });

    // for places venue select
    $(placesHeatMapSelect).change(function () {
        updateMapQuery(placesTimeSelect);
        layerGroup.clearLayers();
        getInterquartiles(this);
    });

});

function updateMapQuery(select) {
    console.log('update map query ...');
    var position = $(select).prop('selectedIndex');
    console.log(position);
    // calculate the time span to query db with
    var dateRange = getDateRange(position, "YYYY-MM-DD HH:mm:ss");
    //set the map query from and to dates!
    mapQuery.from = dateRange.from;
    mapQuery.to = dateRange.to;
}

function submitMapQuery() {
    $('#' + sidebarControl.getActiveTab() + '-info').toggle();
    //remove the current overlays
    console.log(mapQuery);
    layerGroup.clearLayers();
    removeMapLayer(currentOverlay);
    //detect what tab is being used on the sidebarControl
    switch (sidebarControl.getActiveTab()) {
        case 'people':
            console.log('in people switch case');
            //mapQuery.group = $(peopleHeatMapSelect).val();
            //submit the user query
            getInterquartiles(peopleHeatMapSelect)
            //peopleSubmit();
            break;
        case 'places':
            //mapQuery.group = $(placesHeatMapSelect).val();
            //submit the venue query
            placesSubmit();
            break;
        case 'reviews':
            //mapQuery.group = $(reviewHeatMapSelect).val();
            getInterquartiles(reviewHeatMapSelect);
            break;
        case 'breastfeeding-friendliness':
            //mapQuery.group = $(breastfeedingHeatMapSelect).val();
            getInterquartiles(friendlinessHeatMapSelect);
            break;
        default:
            //do nothing
            break;
    }
}

/*
 Using moment.js
 uses the position clicked by the user on the control panel
 to determine what timespan to query the database with
 */
function getDateRange(pos, format) {
    var fromDate, toDate;
    switch (pos) {
        case 0: //today
            toDate = moment().endOf('day').format(format);
            fromDate = moment().startOf('day').format(format);

            break;
        case 1: //yesterday
            fromDate = moment().subtract(1, 'days').startOf('day').format(format);
            toDate = moment().subtract(1, 'days').endOf('day').format(format);

            break;
        case 2: //this week

            fromDate = moment().startOf('isoWeek').format(format);
            toDate = moment().endOf('isoWeek').format(format);

            break;
        case 3: //last week
            fromDate = moment().subtract(1, 'weeks').startOf('isoWeek').format(format);
            toDate = moment().subtract(1, 'weeks').endOf('isoWeek').format(format);
            break;
        case 4: //this month
            fromDate = moment().startOf('month').format(format);
            toDate = moment().endOf('month').format(format);
            break;
        case 5: //last month
            fromDate = moment().subtract(1, 'months').startOf('month').format(format);
            toDate = moment().subtract(1, 'months').endOf('month').format(format);
            break;
        case 6: //last 3 months
            fromDate = moment().subtract(3, 'months').startOf('month').format(format);
            toDate = moment().endOf('month').format(format);
            break;
        case 7: //last 6 months
            fromDate = moment().subtract(6, 'months').startOf('month').format(format);
            toDate = moment().endOf('month').format(format);
            break;
        case 8: //this year
            fromDate = moment().startOf('year').format(format);
            toDate = moment().endOf('year').format(format);
            break;
        case 9: // lifetime
            return {
                from: moment("2013-1-1").format(format),
                to: moment().format(format)
            };
            break;
        default:
            break;

    }
    //return the dates
    return {
        from: fromDate,
        to: toDate
    };
}
/*
 Get the wms layer from geoserver
 uses the quintile calculated to determine the color range
 e.g. >5 red, >10 orange etc
 */
function getWmsTilesInterq(wms) {
    console.log(wms);
    url = geoserverUrl;
    url += '&env=first_q:' + wms[1] +
        ';second_q:' + wms[2] +
        ';third_q:' + wms[3] +
        ';fourth_q:' + wms[4] +
        ';fifth_q:' + wms[5];

    return L.tileLayer.wms(url, {
        layers: 'cite:' + wms.geo_layer_name,
        transparent: true,
        format: 'image/png8',
        styles: wms.geo_layer_style
    });
}

function placesSubmit() {
    $('#venue-info').toggle();
    map.spin(true);
    disableMapInteraction();
    $(placesTimeSelect).prop('disabled', true);
    $(placesHeatMapSelect).prop('disabled', true);
    console.log('in venues form submit ...');
    var url = $(placesHeatMapSelect).val();
    $.when(
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: $.ajaxSettings.url + 'get_venue_in_timepsan',
            data: mapQuery,
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        }),
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: $.ajaxSettings.url + url,
            data: mapQuery,
            success: function (data) {
                console.log('ajax success for ' + url);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        })
    ).done(function (markers, wms) {
            console.log(wms[0]['wms_details']);
            drawVenueMarkers(markers[0]);
            var layer = getWmsTilesInterq(wms[0]['wms_details']);
            layerGroup.addLayer(layer);
        }).always(function () {
            map.spin(false);
            enableMapInteraction();
            $(placesTimeSelect).prop('disabled', false);
            $(placesHeatMapSelect).prop('disabled', false);
        });

}


function getInterquartiles(select) {
    var url = $(select).val();
    disableMapInteraction();
    $(select).prop('disabled', true);
    map.spin(true);
    return $.ajax({
        type: 'GET',
        dataType: 'json',
        url: $.ajaxSettings.url + url,
        data: mapQuery,
        success: function (data) {
            console.log('ajax success for ' + url);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    }).done(function (data) {
        console.log(data);
        drawMarker(data['cluster_data']);
        var layer = getWmsTilesInterq(data['wms_details']);
        console.log(layer);
        layerGroup.addLayer(layer);
        //getWmsTilesInterq(data['wms_details']).addTo(map);
    }).always(function () {
        enableMapInteraction();
        map.spin(false);
        $(select).prop('disabled', false);
    });

}


function drawVenueMarkers(data) {
    console.log(data);
    //clear the current marker layer
    markers.clearLayers();
    var title, marker;
    //used for the anchor link href query
    var index = $(placesTimeSelect).prop('selectedIndex');
    //also used for the anchor link href query
    var dateRange = getDateRange(index, "YYYY-MM-DD");
    for (var i = 0; i < data.length; i++) {
        venue = data[i]['Venue'];
        review = data[i]['Review'];

        lat = venue.lat;
        lng = venue.lng
        title =
            "<div class='span4'>" +
            '<h4>' + venue.name + '</h4>' +
            '<a target="_blank" href=http://localhost/feedfinder/venues?id=' + venue.id +
            '&from=' + dateRange.from + '&to=' + dateRange.to + '>' + review.length + ' review(s)</a>' +
            '<address>' +
            '<strong>' + venue.address + '</strong><br>' +
            venue.city + '<br>' +
            venue.postalCode + '<br>' +
            venue.created + '<br>' +
            '</address>' +
            "</div>";
        var reviewCount = data[i]['Review'].length;
        if (reviewCount > 0) {
            marker = L.marker(new L.latLng(lat, lng), {
                title: title,
                review: data[i]['Review'],
                venues: data[i]['Venue'],
            });
            marker.bindPopup(title);
            markers.addLayer(marker);
        }
    }
    layerGroup.addLayer(markers).addTo(map);
    markerClick();
    clusterClick();
    map.spin(false);

}


function drawMarker(data) {
    //clear current layer
    markers.clearLayers();

    var venue, count;
    for (var i = 0; i < data.length; i++) {
        //get the venue containing lat and lng
        venue = data[i]['Venue'];
        //get the people count
        count = data[i][0]['count'];
        //if the count is greater than zero
        //to avoid location with zero users, too many markers
        if (count > 0) {
            marker = L.marker(new L.latLng(venue.lat, venue.lng), {
                title: count + sidebarControl.getActiveTab(),
                riseOnHover: true
            });
            //marker message
            marker.bindPopup(count + ' ' + sidebarControl.getActiveTab());
            markers.addLayer(marker);
        }
    }
    //add the layer
    layerGroup.addLayer(markers).addTo(map);
    //map.addLayer(markers);
    //the interactive stuff, marker lick and cluster click
    markerClick();
    map.spin(false);
}


function clusterClick() {
    markers.on('clusterclick', function (a) {
        $('#venues-panel').empty();
        console.log(a.layer.getAllChildMarkers());
        var childMarkers = a.layer.getAllChildMarkers();
        for (var i = 0; i < childMarkers.length; i++) {
            var child = childMarkers[i];
            // $('#venues-info').append(child.options.title);
            console.log(a);
        }

    });
}

function markerClick() {
    markers.on('click', function (a) {
        $('#venue-info').empty();
        var review = a.layer.options.review;
        var venue = a.layer.options.venues;
        var title = "<div class='page-header' id='star'>" +
            "<h4>" + venue.name.toUpperCase() + "</h4></div>";
        $('#venue-info').append(title);

        for (var i = 0; i < review.length; i++) {
            var reviewText = review[i].review_text;
            if (reviewText == null) {
                reviewText = 'no comments was left';
            }
            var reviewHtml = "<p>" + reviewText + "</p>";
            var createdHtml = "<p>" + review[i].created + "</p>";

            console.log(title);
            console.log(reviewHtml);
            console.log(createdHtml);

            $('#venue-info').append(reviewHtml);
            $('#venue-info').append(createdHtml);
            $('#venue-info').append('<hr>');
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
