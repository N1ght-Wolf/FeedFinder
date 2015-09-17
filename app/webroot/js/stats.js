var form;
var userSelect, venueSelect;
var markers;
$.ajaxSetup({
	url: getBaseURL() + 'feed_finder_transactions/',
	dataType: 'json'
});

$(document).ready(function() {
	sidebar.open('home');
	markers = new L.MarkerClusterGroup();

	form = $('.query-form');
	userSelect = $(form).eq(0).find('select');
	venueSelect = $(form).eq(1).find('select');


	var selectors = [userSelect, venueSelect];
	$(selectors).each(function() {
		$(this).change(function() {
			var position = $(this).prop('selectedIndex');
			console.log(position);
			var formData = getDateRange(position, "YYYY-MM-DD HH:mm:ss");
			formPackage(formData);
		});
	});
});




function formPackage(formData) {
	$('#' + sidebar.getActiveTab() + '-info').toggle();
	switch (sidebar.getActiveTab()) {
		case 'users':
			url = 'get_stats_users'
			usersFormSubmit(formData);
			break;
		case 'venues':
			venuesFormSubmit(formData);
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

function getWmsTiles(data) {
	var first_q = data.first_q;
	var second_q = data.second_q;
	var third_q = data.third_q;
	var geoserverLayer = data.geo_layer_name;
	return L.tileLayer.wms(geoserverUrl +
		'?SERVICE=WMS&REQUEST=GetMap&env=first_q:' + first_q + ';second_q:' + second_q + ';third_q:' + third_q + ';&VERSION=1.1.0', {
			layers: 'cite:' + geoserverLayer,
			format: 'image/png',
			transparent: true,
			version: '1.1.0',
			tiled: true,
			attribution: "myattribution",
		});
}

function getAverageRating(formData, groupBy, model) {
	return $.ajax({
		type: 'GET',
		url: $.ajaxSettings.url + "average_rating",
		data: {
			form: formData,
			group: groupBy,
			model: model
		},
		success: function(data) {
			console.log('ajax success from: venues...');
			console.log(data);
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	})
}

function venuesFormSubmit(formData) {
	$('#venue-info').toggle();
	map.spin(true);
	console.log('in venues form submit ...');
	$.when(
		$.ajax({
			type: 'GET',
			url: $.ajaxSettings.url + "get_stats_venues",
			data: formData,
			success: function(data) {
				console.log('ajax success from: venues...');
				console.log(data);
				drawMarkers(data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		}),
		//get the average ratings
		getAverageRating(formData, 'Venue.iso', 'World'),
		getAverageRating(formData, 'Venue.city', 'AdminOne'),
		getAverageRating(formData, 'Venue.address', 'UkAdminThree'),
		//get interquartile range
		getInterquartiles(formData, 'review_interq_ukadminthree'),
		getInterquartiles(formData, 'review_interq_adminone'),
		getInterquartiles(formData, 'review_interq_world')
	).done(function(a, b, c, d, e, f, g) {
		worldRating = getWmsTiles(b[0]);
		adminOneRating = getWmsTiles(c[0]);
		ukAdminThreeRating = getWmsTiles(d[0]);
		var ukInterQ = getWmsTiles(e[0]);
		var adminOneInterQ = getWmsTiles(f[0]);
		var worldInterQ = getWmsTiles(g[0]);

		var overlays = [{
			groupName: "Review count",
			expanded: true,
			layers: {
				'country': worldInterQ,
				'county': adminOneInterQ,
				'UK SOA': ukInterQ
			}
		}, {
			groupName: "Review rating",
			expanded: true,
			layers: {
				"country": worldRating,
				"county": adminOneRating,
				"UK SOA": ukAdminThreeRating
			}
		}];

		var options = {
			container_width: "300px",
			group_maxHeight: "80px",
			//container_maxHeight : "350px",
			exclusive: false
		};
		// Use the custom grouped layer control, not "L.control.layers"
		var control = L.Control.styledLayerControl(null, overlays, options);
		map.addControl(control);

		map.spin(false);


	});

}

function usersFormSubmit(formData) {
	console.log('in users form submit ...');
	map.spin(true);
	removeChoroplethLayers();
	disableMapInteraction();
	$.when(
		getInterquartiles(formData, 'users_interq_ukadminone'),
		getInterquartiles(formData, 'users_interq_adminone'),
		getInterquartiles(formData, 'users_interq_world')
	).done(function(a, b, c) {
		ukAdminThree = getWmsTiles(a[0]);
		adminOne = getWmsTiles(b[0]);
		world = getWmsTiles(c[0]);
		world.addTo(map);
		layers.push(world);
		layers.push(adminOne);
		layers.push(ukAdminThree);
		enableMapInteraction();
		map.spin(false);
	});

}


function getInterquartiles(formData, url) {
	return $.ajax({
		type: 'GET',
		url: $.ajaxSettings.url + url,
		data: formData,
		success: function(data) {
			console.log('ajax success from: review...');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	})
}



function drawMarkers(data) {
	console.log('in drawing markers ...');
	markers.clearLayers();
	var title, marker;
	var index = $(venueSelect).prop('selectedIndex');
	var dateRange = getDateRange(index, "YYYY-MM-DD");
	console.log(dateRange);
	for (var i = 0; i < data.length; i++) {
		venue = data[i]['Venue'];
		review = data[i]['Review'];

		lat = venue.lat;
		lng = venue.lng
		title = "	<div class='span4'>" +
			'<h4>' + venue.name + '</h4>' +
			'<a target="_blank" href=http://localhost/am-analytics/venues?id=' + venue.id + '&from=' + dateRange.from + '&to=' + dateRange.to + '>' + review.length + ' review(s)</a>' +
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
	map.spin(false);

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
