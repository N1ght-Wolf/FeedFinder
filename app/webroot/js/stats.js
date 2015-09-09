var form;
var userSelect, reviewSelect, venueSelect;
var markers;
$.ajaxSetup({
	url: getBaseURL() + 'feed_finder_transactions/',
	dataType: 'json'
});

$(document).ready(function() {
	sidebar.open('home');
	markers = new L.MarkerClusterGroup();

	form = $('.query-form');
	var userSelect = $(form).eq(0).find('select');
	var reviewSelect = $(form).eq(1).find('select');
	var venueSelect = $(form).eq(2).find('select');

	var selectors = [userSelect, reviewSelect, venueSelect];
	$(selectors).each(function() {
		$(this).change(function() {
			console.log('clicked');
			var position = $(this).prop('selectedIndex');
			var formData = getDateRange(position, this);
			formPackage(formData);
		});
	});


});




function formPackage(formData) {
  $('#'+sidebar.getActiveTab()+'-info').toggle();
	switch (sidebar.getActiveTab()) {
		case 'review':

			reviewFormSubmit(formData);
			break;
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



function getDateRange(pos, select) {
	var fromDate, toDate;
	console.log(pos);
	switch (pos) {
		case 1: //today
			toDate = moment().endOf('day').format("YYYY-MM-DD  HH:mm:ss");
			fromDate = moment().startOf('day').format("YYYY-MM-DD  HH:mm:ss");

			break;
		case 2: //yesterday
			fromDate = moment().subtract(1, 'days').startOf('day').format("YYYY-MM-DD  HH:mm:ss");
			toDate = moment().subtract(1, 'days').endOf('day').format("YYYY-MM-DD  HH:mm:ss");

			break;
		case 3: //this week

			fromDate = moment().startOf('isoWeek').format("YYYY-MM-DD  HH:mm:ss");
			toDate = moment().endOf('isoWeek').format("YYYY-MM-DD  HH:mm:ss");

			break;
		case 4: //last week
			fromDate = moment().subtract(1, 'weeks').startOf('isoWeek').format("YYYY-MM-DD  HH:mm:ss");
			toDate = moment().subtract(1, 'weeks').endOf('isoWeek').format("YYYY-MM-DD  HH:mm:ss");
			break;
		case 5: //this month
			fromDate = moment().startOf('month').format("YYYY-MM-DD  HH:mm:ss");
			toDate = moment().endOf('month').format("YYYY-MM-DD  HH:mm:ss");
			break;
		case 6: //last month
			fromDate = moment().subtract(1, 'months').startOf('month').format("YYYY-MM-DD  HH:mm:ss");
			toDate = moment().subtract(1, 'months').endOf('month').format("YYYY-MM-DD  HH:mm:ss");
			break;
		case 7: //last 3 months
			fromDate = moment().subtract(3, 'months').startOf('month').format("YYYY-MM-DD  HH:mm:ss");
			toDate = moment().endOf('month').format("YYYY-MM-DD  HH:mm:ss");
			break;
		case 8: //last 6 months
			fromDate = moment().subtract(6, 'months').startOf('month').format("YYYY-MM-DD  HH:mm:ss");
			toDate = moment().endOf('month').format("YYYY-MM-DD  HH:mm:ss");
			break;
		case 9: //this year
			fromDate = moment().startOf('year').format("YYYY-MM-DD  HH:mm:ss");
			toDate = moment().endOf('year').format("YYYY-MM-DD  HH:mm:ss");
			break;
		case 10: // lifetime
			return {
				from: moment("2013-1-1").format("YYYY-MM-DD  HH:mm:ss"),
				to: moment().format("YYYY-MM-DD  HH:mm:ss")
			};
		default:
			break;

	}
	console.log('date requested');
	console.log('from date: ' + fromDate);
	console.log('to date: ' + toDate);
	return {
		from: fromDate,
		to: toDate
	};
}


function reviewFormSubmit(formData) {
	console.log('in review form submit ...');
	map.spin(true);
	removeChoroplethLayers();
	disableMapInteraction();
	$.when(
		ajaxSubmit(formData, 'review_interq_ukadminthree'),
		ajaxSubmit(formData, 'review_interq_adminone'),
		ajaxSubmit(formData, 'review_interq_world')
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

function getWmsTiles(data) {
	var first_q = data.first_q;
	var second_q = data.second_q;
	var third_q = data.third_q;
	var geoserverLayer = data.geo_layer_name;
	console.log(geoserverLayer);

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

function venuesFormSubmit(formData) {
  $('#venue-info').toggle();
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
		$.ajax({
			type: 'GET',
			url: $.ajaxSettings.url + "average_rating",
			data: {form:formData, group:'Venue.iso',model:'World'},
			success: function(data) {
				console.log('ajax success from: venues...');
				console.log(data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		}),
		$.ajax({
			type: 'GET',
			url: $.ajaxSettings.url + "average_rating",
			data: {form:formData, group:'Venue.city',model:'AdminOne'},
		}),
		$.ajax({
			type: 'GET',
			url: $.ajaxSettings.url + "average_rating",
			data: {form:formData, group:'Venue.address',model:'UkAdminThree'},
		})
	).done(function(a,b,c,d){
		console.log('here');
		console.log(a);
		console.log(b);
		console.log(c);
		console.log(d);
		world = getWmsTiles(b[0]);
		adminOne = getWmsTiles(c[0]);
		ukAdminThree = getWmsTiles(d[0]);
		world.addTo(map);

	});

}

function usersFormSubmit(formData) {
	console.log('in users form submit ...');
	map.spin(true);
	removeChoroplethLayers();
	disableMapInteraction();
	$.when(
		ajaxSubmit(formData, 'users_interq_ukadminone'),
		ajaxSubmit(formData, 'users_interq_adminone'),
		ajaxSubmit(formData, 'users_interq_world')
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

function ajaxSubmit(formData, url) {
	return $.ajax({
		type: 'GET',
		url: $.ajaxSettings.url + url,
		data: formData,
		success: function(data) {
			console.log('ajax success from: users...');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	})
}

function getGraphData(formData) {
	console.log('getting graph data ...');
	var endUrl = $(form).attr('action');

	$(form).ajaxSubmit({
		type: 'GET',
		dataType: 'json',
		url: getBaseURL() + 'feed_finder_transactions/' + endUrl,
		data: formData,
		success: function(data) {
			console.log('recieved graph data');
			drawGraph(data, 'review');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}

	});

}

function drawGraph(data, seriesDescription) {
	$('#graph-container').highcharts('StockChart', {

		chart: {},

		navigator: {
			handles: {
				backgroundColor: 'yellow',
				borderColor: 'red'
			}
		},

		rangeSelector: {
			selected: 1
		},

		series: [{
			name: seriesDescription,
			data: data,
			type: 'area',
			fillColor: {
				linearGradient: {
					x1: 0,
					y1: 0,
					x2: 0,
					y2: 1
				},
				stops: [
					[0, Highcharts.getOptions().colors[0]],
					[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
				]
			}
		}]
	});
}

function drawMarkers(data) {
	console.log('in drawing markers ...');
	markers.clearLayers();
	var title, marker;
	console.log(markers);
	for (var i = 0; i < data.length; i++) {
    venue = data[i]['Venue'];
		review = data[i]['Review'];

		lat = venue.lat;
		lng = venue.lng
    title="	<div class='span4'>"+
  		    		'<h4>'+venue.name+'</h4>'+
							'<a>'+review.length+' review(s)</a>'+
  		    		'<address>'+
  		    			'<strong>'+venue.address+'</strong><br>'+
  		    			venue.city+'<br>'+
                venue.postalCode+'<br>'+
                venue.created+'<br>'+
  		    		'</address>'+
  		    	"</div>";
		length = data[i]['Review'].length;
		if (length > 0) {
			marker = L.marker(new L.latLng(lat, lng), {
				title: title,
				review:data[i]['Review'],
				venues:data[i]['Venue'],
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
		var title = "<div class='page-header' id='star'>"+
				"<h4>"+venue.name.toUpperCase()+"</h4></div>";
				$('#venue-info').append(title);

		for(var i=0; i<review.length; i++){
			var reviewText =  review[i].review_text;
			if(reviewText == null){
				reviewText = 'no comments was left';
			}

			var reviewHtml = "<p>"+reviewText+"</p>";
			var createdHtml ="<p>"+ review[i].created+"</p>";


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
    for(var i=0; i<childMarkers.length; i++){
			var child =  childMarkers[i];
			// $('#venues-info').append(child.options.title);
			console.log(a);
    }
	  //  var obj = JSON.parse(a.layer.options.title);
		 //
    //  $('#venue-stats').empty();
    //  $( "<p>Location Name: "+obj.Venue.name+"</p>" ).appendTo( "#venue-stats" );
    //  $('#venue-stats').show();
    //  console.log(a);

	});

	//stopped here!, trying to add the markers to the map
	map.spin(false);

}

function onMarkerClick(e) {
	alert('e.target._myId');
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
