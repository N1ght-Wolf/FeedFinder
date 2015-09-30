var form;
var userSelect, venueSelect;
var markers;
var overlays, toggleControl,dateRange;
var mapQuery = {
	from: 0,
	to: 0
};

var options = {
	container_width: "300px",
	group_maxHeight: "80px",
	exclusive: false,
	collapsed:false
};

$.ajaxSetup({
	url: getBaseURL() + 'feed_finder_transactions/',
});

$(document).ready(function() {
	sidebar.open('venues');
	markers = new L.MarkerClusterGroup();

	form = $('.query-form');
	userSelect = $(form).eq(0).find('select');
	venueSelect = $(form).eq(1).find('select');
	$(venueSelect).prop('selectedIndex', 6);
	var selectors = [userSelect, venueSelect];
	$(selectors).each(function() {
		$(this).change(function() {
			var position = $(this).prop('selectedIndex');
			console.log(position);
			dateRange = getDateRange(position, "YYYY-MM-DD HH:mm:ss");
			mapQuery.from = dateRange.from;
			mapQuery.to = dateRange.to;
			submitMapQuery();
		});
	});
	var position = $(venueSelect).prop('selectedIndex');
	dateRange = getDateRange(position, "YYYY-MM-DD HH:mm:ss");
	mapQuery.from = dateRange.from;
	mapQuery.to = dateRange.to;
	submitMapQuery();
});




function submitMapQuery() {
	$('#' + sidebar.getActiveTab() + '-info').toggle();
	console.log(currentOverlay);
	switch (sidebar.getActiveTab()) {
		case 'users':
			removeToggle();
			usersFormSubmit();
			markers.clearLayers();
			if(map.hasLayer(currentOverlay)){
				map.removeLayer(currentOverlay);
			}
			break;
		case 'venues':
			removeToggle();
			venuesFormSubmit();
			if(map.hasLayer(currentOverlay)){
				map.removeLayer(currentOverlay);
			}
			break;
		default:
			//do nothing
			break;
	}
}


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
	return {
		from: fromDate,
		to: toDate
	};
}

function getWmsTilesInterq(wms) {
	url = geoserverUrl;
	url += '&env=first_q:' + wms[1] +
		';second_q:' + wms[2] +
		';third_q:' + wms[3] +
		';fourth_q:' + wms[4] +
		';fifth_q:' + wms[5];
	console.log(url);
	return L.tileLayer.betterWms(url, {
			layers: 'cite:' + wms.geo_layer_name,
			transparent: true,
			format: 'image/png',
			styles: wms.geo_layer_style
		});
}

function getWmsTilesRatings(wms) {
	return L.tileLayer.betterWms(geoserverUrl, {
			layers: 'cite:' + wms.geo_layer_name,
			transparent: true,
			format: 'image/png',
			styles: wms.geo_layer_style
		});
}

function getAverageRating(url) {
	return $.ajax({
		type: 'GET',
		dataType: 'json',
		url: $.ajaxSettings.url + url,
		data: mapQuery,
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown, jqXHR);
		}
	});
}

function venuesFormSubmit() {
	$('#venue-info').toggle();
	map.spin(true);
	console.log('in venues form submit ...');
	$.when(
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: $.ajaxSettings.url + "get_stats_venues",
			data: mapQuery,
			success: function(data) {
				console.log('ajax success from: venues...');
				console.log(data);
				drawMarkers(data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		}),
		getAverageRating('average_rating_world'),
		getAverageRating('average_rating_admin_one'),
		getAverageRating('average_rating_uk')

	).done(function(makers, avgRatingWorld, avgRatingAdminOne, avgRatingUk) {
		$.when(
			getInterquartiles('review_interq_ukadminthree'),
			getInterquartiles('review_interq_adminone'),
			getInterquartiles('review_interq_world')
		).done(function(reviewCountUk, reviewCountAdminOne, reviewCountWorld) {
			removeToggle();

			overlays = [{
				groupName: "Review count",
				expanded: true,
				layers: {
					'country': getWmsTilesInterq(reviewCountWorld[0]),
					'county': getWmsTilesInterq(reviewCountAdminOne[0]),
					'UK SOA': getWmsTilesInterq(reviewCountUk[0])
				}
			}, {
				groupName: "Review rating",
				expanded: true,
				layers: {
					"country": getWmsTilesRatings(avgRatingWorld[0]),
					"county": getWmsTilesRatings(avgRatingAdminOne[0]),
					"UK SOA": getWmsTilesRatings(avgRatingUk[0])
				}
			}];
			toggleControl = L.Control.styledLayerControl(null, overlays, options);
			map.addControl(toggleControl);
			map.spin(false);
		});
	});

}

function usersFormSubmit() {
	console.log('in users form submit ...');
	map.spin(true);
	disableMapInteraction();
	$.when(
		getInterquartiles('users_interq_ukadminthree'),
		getInterquartiles('users_interq_adminone'),
		getInterquartiles('users_interq_world')
	).done(function(uk, adminOne, world) {
		removeToggle();
		overlays = [{
			groupName: "User count",
			expanded: true,
			layers: {
				'country': getWmsTilesInterq(world[0]),
				'county': getWmsTilesInterq(adminOne[0]),
				'UK SOA': getWmsTilesInterq(uk[0])
			}
		}];
		toggleControl = L.Control.styledLayerControl(null, overlays, options);
		map.addControl(toggleControl);

		enableMapInteraction();
		map.spin(false);
	});
}

function removeToggle() {
	if (toggleControl != undefined) {
		map.removeControl(toggleControl);
		toggleControl.removeGroup('User count');
		toggleControl = null;
		overlays = null;
	}
}

function getInterquartiles(url) {
	console.log($.ajaxSettings.url + url);
	return $.ajax({
		type: 'GET',
		dataType: 'json',
		url: $.ajaxSettings.url + url,
		data: mapQuery,
		success: function(data) {
			console.log('ajax success from: review...');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	});
}



function drawMarkers(data) {
	console.log('in drawing markers ...');
	markers.clearLayers();
	var title, marker;
	var index = $(venueSelect).prop('selectedIndex');
	var dateRange = getDateRange(index, "YYYY-MM-DD");
	for (var i = 0; i < data.length; i++) {
		venue = data[i]['Venue'];
		review = data[i]['Review'];

		lat = venue.lat;
		lng = venue.lng
		title =
			"<div class='span4'>" +
			'<h4>' + venue.name + '</h4>' +
			'<a target="_blank" href=http://localhost/am-analytics/venues?id=' + venue.id +
			'&from=' + dateRange.from + '&to=' + dateRange.to + '>' + review.length + ' review(s)</a>' +
			'<address>' +
			'<strong>' + venue.address + '</strong><br>' +
			venue.city + '<br>' +
			venue.postalCode + '<br>' +
			venue.created + '<br>' +
			'</address>' +
			"</div>";
		length = data[i]['Review'].length;
		if (length > 0) {
			marker = L.marker(new L.latLng(lat, lng), {
				title: title,
				review: data[i]['Review'],
				venues: data[i]['Venue'],
				riseOnHover: true
			});
			marker.bindPopup(title);
			markers.addLayer(marker);
		}
	}

	map.addLayer(markers);
	markerClick();
	clusterClick();

	map.spin(false);

}

function clusterClick() {
	markers.on('clusterclick', function(a) {
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
	markers.on('click', function(a) {
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
