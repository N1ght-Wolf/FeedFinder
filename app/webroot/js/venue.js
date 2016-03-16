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
    rating_low: 0,
    rating_high: 0
};

$(document).ready(function () {
    //grab the venue id from url
    venue.id = $.url('?id');
    //set the query url
    query.id = venue.id;
    //get the from and to dates from url
    query.from = $.url('?from');
    query.to = $.url('?to');
    //default start and end index for pagination
    query.start = 1;
    query.end = 5;
    //default review order
    query.order = 'ASC';

    //get the amount of reviews available for the given time span
    //the from and to date is already set in the query object
    getReviewCount(query).done(function (numOfReviews) {
        $('#review-count').text(numOfReviews + ' review(s)');
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
            paginate.on("page", function (event, num) {
                event.preventDefault();
                //when pagination index is changed
                //set the new start index to fetch from database
                query.start = num;
                //fetch from new index
                getReviews(query).done(function (data) {
                    console.log(data);
                    showReviews(data);
                });
                $("#review-content").html("Page " + num); // or some ajax content loading...
            });
        }

    });
    //getting the time or date range selector
    time = $('#comment-form > div:nth-child(1) > select');
    //getting the sort order selector
    sort = $('#comment-form > div:nth-child(2) > select');

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

    /* store perfomance links in array
     so we can loop through them and use
     on click at the same time
     */

    var performanceLinks = [
        excellentLink,
        vGoodLink,
        averageLink,
        poorLink,
        terribleLink
    ];

    /* store selectors in array
     so we can loop through them and use
     on click at the same time
     */
    var selectors = [time, sort];

    $(selectors).each(function () {
        $(this).change(function () {
            $("#review-content").empty();
            updateQuery();
        })
    });
    //loop through each <a> and detect which was clicked
    $(performanceLinks).each(function () {
        $(this).click(function (event) {
            console.log('link clicked');
            event.preventDefault();
            $('#filter-icon').remove();
            $(this).after(" <i onclick='removeFilter()' id='filter-icon' class='fa fa-times-circle'" +
                "style='color:red;'></i>")
            $('#review-content').empty();
            //grab the href information
            var href = $(this).attr("href");
            //get the query in the href
            var ratingObj = $.url('?', href);
            //extract the high and low bounds to categorize the ratings
            query.rating_low = ratingObj.rating_low;
            query.rating_high = ratingObj.rating_high;
            //get the reviews
            updateQuery();
        });
    });


});
/*
 * removes the currently selected filter
 * then fetches all the review for the venue
 * */
function removeFilter() {
    //remove filter
    $('#filter-icon').remove();
    removeReviewValues();
    //reset the review boundaries
    query.rating_low = 0;
    query.rating_high = 0;
    //update and fetch reviews
    updateQuery();
}


function updateQuery() {
    //get the selected position for the date range selector
    var position = $(time).prop('selectedIndex');
    //get the formatted dates using the positions
    var dates = getDateRange(position, "YYYY-MM-DD HH:mm:ss");
    //setting the from date
    query.from = dates.from;
    //setting the to date
    query.to = dates.to;
    //the order the results should be sorted
    query.order = $(sort).val();
    query.start = 1;
    // find how many pagination index we will need
    getReviewCount(query).done(function (numOfReviews) {
        venue.reviews.numOfReviews = numOfReviews;
        $('#review-count').text(numOfReviews + ' review(s)');
        numberOfReviews = numOfReviews;
        //check the is at least one review
        if (venue.reviews.numOfReviews > 0) {
            //hide the no reviews message
            $('#no-reviews').hide();
            //set the amount of paginations we want
            $(paginate).bootpag({
                total: Math.ceil(numOfReviews / 5)
            });
            // fetch the venue query
            submitQuery(query);
        } else {
            $(paginate).bootpag({
                total: 1
            });
            //empty the venues data
            removeAllVenueValues();
            //show the no reviews message
            $('#no-reviews').show();
        }

    });

}

function submitQuery(query) {
    $.when(
        getReviews(query),
        getRatings(query)
    ).done(function (reviews, ratings) {
            console.log(reviews);
            console.log(ratings);
            showReviews(reviews[0]);
            showRatings(ratings[0]);
            showPerformance(reviews[0]);
        });
}
/*
 * Takes the given reviews and updates the venue obj
 * then renders the reviews
 * */
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


// function getReview(query) {
//     return $.ajax({
//         url: getBaseURL() + 'venues/get_reviews',
//         data: query,
//         dataType: 'json'
//     });
// }

function getReviews(formData) {
    return $.ajax({
        url: getBaseURL() + 'venues/get_review',
        data: formData,
        dataType: 'json'
    });
}
/*
 * Removes all the progress bar values
 * removes all the rating values
 * removes all the reviews
 * */
function removeAllVenueValues() {
    removePrefValues();
    removeRatingValues();
    removeReviewValues();
}

function removePrefValues() {

    $.each(venue.performances, function (key, value) {
        venue.performances[key] = 0
    });
    updateProgBar();
}

function removeRatingValues() {
    $.each(venue.ratings, function (key, value) {
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
    }).done(function (data) {
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
            query: query
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
    }).done(function (data) {
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
    $(progBars).each(function (index) {
        $(progBars[index]).empty();
    })
}

function displayReviews(reviews) {
    console.log(reviews);
    for (var i = 0; i < reviews.length; i++) {
        if (reviews[i].Review.review_text != null) {
            $('.comment-list').append("<header class='text-left' id='comment-header'>" +
                "<div class='comment-user'><i class='fa fa-user'></i> " + reviews[i].User.username + "</div>" +
                "<time class='comment-date' datetime='16-12-2014 01:05'><i class='fa fa-clock-o'></i> " + moment(reviews[i].Review.created).format("dddd, MMMM Do YYYY") + "</time>" +
                "</header>" +
                "<div class='comment-post review-comment'>" +
                "<p>" + reviews[i].Review.review_text + "</p><div><hr>");
        }
        else {
            $('.comment-list').append("<header class='text-left' id='comment-header'>" +
                "<div class='comment-user'><i class='fa fa-user'></i> " + reviews[i].User.username + "</div>" +
                "<time class='comment-date' datetime='16-12-2014 01:05'><i class='fa fa-clock-o'></i> " + moment(reviews[i].Review.created).format("dddd, MMMM Do YYYY") + "</time>" +
                "</header>" +
                "<div class='comment-post review-comment'>" +
                "<p class='bg-warning'>" + 'This user did not leave a review' + "</p><div><hr>");
        }

    }
}
