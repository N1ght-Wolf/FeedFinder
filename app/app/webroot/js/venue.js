var paginate;
var time, sort;
var numberOfReviews;
var excellent, vGood, average, poor, terrible;
var excellentLink, vGoodLink, averageLink, poorLink, terribleLink;
var comfort, cleanliness, privacy, babyFac, averageSpend;
var progBars;

var venue = {
	id: 0,
	ratings: {},
	performances: {},
	reviews: {},

};
var query = {
	id: venue.id,
	from: '',
	to: '',
	order: '',
	start: 0,
	end: 0,
  rating_low:0,
  rating_high:0
};

$(document).ready(function() {

	venue.id = $.url('?id');
	query.id = venue.id;
	query.from = $.url('?from');
	query.to = $.url('?to');
	query.start = 1;
	query.end = 5;
	query.order = 'ASC';

	getReviewCount(query).done(function(numOfReviews) {
		$('#review-count').empty();
		$('#review-count').text(numOfReviews+" review(s)");
		venue.reviews.numOfReviews = numOfReviews;
		numberOfReviews = numOfReviews;

		//if the venue reviews are greater than 0
		if (venue.reviews.numOfReviews > 0) {
			$('#no-reviews').hide();
			//initialise pagination size
			paginate = $('#page-selection').bootpag({
				//five reviews per paginated index
				total: Math.ceil(venue.reviews.numOfReviews / 5)
			});

			submitQuery(query);
			paginate.on("page", function(event, num) {
				event.preventDefault();
				//when pagination index is changed
				//set the new start index to fetch from database
				query.start = num;
				//fetch from new index
				getReviews(query).done(function(data) {
					console.log(data);
					showReviews(data);
				});
				$("#review-content").html("Page " + num); // or some ajax content loading...
			});
		}

	});

	excellent = $('#excellent-progbar');
	vGood = $('#vgood-progbar');
	average = $('#average-progbar');
	poor = $('#poor-progbar');
	terrible = $('#terrible-progbar');

	comfort = $('#comfort-rating');
	cleanliness = $('#cleanliness-rating');
	privacy = $('#privacy-rating');
	babyFac = $('#baby-fac-rating');
	averageSpend = $('#average-spend-rating');

	excellentLink = $('#excellent-link');
	vGoodLink = $('#vgood-link');
	averageLink = $('#average-link');
	poorLink = $('#poor-link');
	terribleLink = $('#terrible-link');

	var performanceLinks = [
		excellentLink,
		vGoodLink,
		averageLink,
		poorLink,
		terribleLink
	];

	time = $('#comment-form > div:nth-child(1) > select')
	sort = $('#comment-form > div:nth-child(2) > select');
	var selectors = [time, sort];
	//
	$(selectors).each(function() {
		$(this).change(function() {
			$("#review-content").empty();
			updateQuery();
		})
	});

	$(performanceLinks).each(function() {
		$(this).click(function(event){
			console.log('link clicked');
			event.preventDefault();
			$('#review-content').empty();
			switchLink($(this).data().star)
		});
	});

	// $('.performance-review-link').click(function(event) {
	// 	// console.log('link clicked');
	// 	// event.preventDefault();
	// 	// $('#review-content').empty();
  //   // switchLink($(this).data().star)
	// })



  $('#filter-icon').click(function(){
    query.rating_low = 0;
    query.rating_high = 0;
    updateQuery();
    $(this).hide();
  });






});
function switchLink(data){
	console.log(data);
  var numOfReviews;
  switch (data) {
    case 5:
    //make ajax called
    $('#filter-icon').show();
    query.rating_low =5;
    query.rating_high = 6;
      break;
    case 4:
    $('#filter-icon').show();
    query.rating_low =4;
    query.rating_high = 5;
    break;
    case 3:
    $('#filter-icon').show();
    query.rating_low =3;
    query.rating_high = 4;
    break;
    case 2:
    $('#filter-icon').show();
    query.rating_low =2;
    query.rating_high = 3;
    break;
    case 1:
    $('#filter-icon').show();
    query.rating_low =1;
    query.rating_high = 2;
    break;
    default:
    break;
  }
	updateQuery();
  paginate = $('#page-selection').bootpag({
    total: Math.ceil(numOfReviews / 5)
  });

}

function updateQuery() {
	var position = $(time).prop('selectedIndex');
	var dates = getDateRange(position, "YYYY-MM-DD HH:mm:ss");
	query.from = dates.from;
	query.to = dates.to;
	query.order = $(sort).val();
	query.start = 1;
	getReviewCount(query).done(function(numOfReviews) {
		$('#review-count').empty();
		$('#review-count').text(numOfReviews+" reviews(s)");
		venue.reviews.numOfReviews = numOfReviews;
		numberOfReviews = numOfReviews;
		if (venue.reviews.numOfReviews > 0) {
			$('#no-reviews').hide();
			$(paginate).bootpag({
				total: Math.ceil(numOfReviews / 5)
			});

			submitQuery(query);
		} else {
			$(paginate).bootpag({
				total: 1
			});
			removeAllVenueValues();
			$('#no-reviews').show();
		}

	});

}

function submitQuery(query) {
	$.when(
		getReviews(query),
		getRatings(query)
	).done(function(reviews, ratings) {
		showReviews(reviews[0]);
		showRatings(ratings[0]);
		showPerformance(reviews[0]);
	});
}

function showReviews(reviews) {
	venue.reviews.venueReviews = reviews;
	displayReviews(reviews.paginated);
}

function showRatings(ratings) {
	venue.ratings = ratings[0];
	updateRatings();
}

function showPerformance(perf) {
	venue.performances = perf.performances;
	venue.performances.excellent = calcPercentage(perf.performances.excellent);
	venue.performances.veryGood = calcPercentage(perf.performances.v_good);
	venue.performances.average = calcPercentage(perf.performances.average);
	venue.performances.poor = calcPercentage(perf.performances.poor);
	venue.performances.terrible = calcPercentage(perf.performances.terrible);
	updateProgBar();
}


function getReview(query) {
	return $.ajax({
		url: getBaseURL() + 'venues/get_reviews',
		data: query,
		dataType: 'json'
	});
}

function getReviews(formData) {
	return $.ajax({
		url: getBaseURL() + 'venues/get_review',
		data: formData,
		dataType: 'json'
	});
}

function removeAllVenueValues() {
	removePrefValues();
	removeRatingValues();
	removeReviewValues();
}

function removePrefValues() {

	$.each(venue.performances, function(key, value) {
		venue.performances[key] = 0
	});
	updateProgBar();
}

function removeRatingValues() {
	$.each(venue.ratings, function(key, value) {
		venue.ratings[key] = 0
	});
	updateRatings();
}

function removeReviewValues() {
	venue.reviews.numOfReviews = 0;
	venue.reviews.venueReviews = {};
	$('review-content').empty();
}

function getReviewByVenueId(id, num, order) {
	$.ajax({
		url: getBaseURL() + 'venues/get_reviews',
		data: {
			id: id,
			start: num,
			end: 5,
			order: order
		},
		dataType: 'json'
	}).done(function(data) {
		if (data.length > 0) {
			appendReview(data);
		} else {
			$('#no-reviews').show();
		}
	});
}

function getReviewCount(query) {
	return $.ajax({
		url: getBaseURL() + 'venues/get_count',
		data: {
			query:query
		},
		dataType: 'json'
	});
}

function getRatings(query) {
	return $.ajax({
		url: getBaseURL() + 'venues/get_venue_ratings',
		data: query,
		dataType: 'json'
	})
}

function updateRatings() {
	$(comfort).rating('rate', venue.ratings.comfort);
	$(cleanliness).rating('rate', venue.ratings.cleanliness);
	$(privacy).rating('rate', venue.ratings.privacy);
	$(babyFac).rating('rate', venue.ratings.baby_fac);
	$(averageSpend).rating('rate', venue.ratings.baby_fac);
}

function updateProgBar() {
	$(excellent).width(venue.performances.excellent);
	$(vGood).width(venue.performances.veryGood);
	$(average).width(venue.performances.average);
	$(poor).width(venue.performances.poor);
	$(terrible).width(venue.performances.terrible);
}

function getPerformance(formData) {
	$.ajax({
		url: getBaseURL() + 'venues/get_venue_perform',
		data: formData,
		dataType: 'json'
	}).done(function(data) {
		updateProgBar(data);
		emptyProgBarValues();
	});
}


function calcPercentage(val) {
	if (numberOfReviews > 0) {
		return (val / numberOfReviews) * 100;
	} else {
		return 0;
	}

}

function emptyProgBarValues() {
	$(progBars).each(function(index) {
		$(progBars[index]).empty();
	})
}

function displayReviews(reviews) {
  console.log(reviews);
	for (var i = 0; i < reviews.length; i++) {
		$('.comment-list').append("<header class='text-left' id='comment-header'>" +
			"<div class='comment-user'><i class='fa fa-user'></i> " + reviews[i].User.username + "</div>" +
			"<time class='comment-date' datetime='16-12-2014 01:05'><i class='fa fa-clock-o'></i> " + moment(reviews[i].Review.created).format("dddd, MMMM Do YYYY") + "</time>" +
			"</header>" +
			"<div class='comment-post review-comment'>" +
			"<p>" + reviews[i].Review.review_text + "</p><div><hr>");
	}
}
