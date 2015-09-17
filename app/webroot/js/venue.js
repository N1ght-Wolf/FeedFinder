var paginate;
var query ={}
var time,sort;
var numberOfReviews;
var excellent,vGood, average, poor, terrible;
var progBars;
var performance = {
  excellent:0,
  veryGood:0,
  average:0,
  poor:0,
  terrible:0
};
var rating ={
  babyFac:0,
  privacy:0,
  comfort:0,
  hygiene:0
};
$(document).ready(function() {

  query.id = $.url('?id');
  query.from = $.url('?from');
  query.to = $.url('?to');
  query.start = 1;
  query.end = 5;
  query.order = 'ASC';

  getReviewCount(query).done(function(numOfReviews){
    numberOfReviews = numOfReviews;
    if(numOfReviews >0){
      $('#no-reviews').hide();
    paginate = $('#page-selection').bootpag({
        total: Math.ceil(numOfReviews/5)
    });


    submitCommentForm(query);
    getVenueAttributeRating(query);
    getVenuePerformance(query);

    paginate.on("page", function(event, num){
      event.preventDefault();
      query.start = num;
      submitCommentForm(query);
      $("#review-content").html("Page " + num); // or some ajax content loading...
    });
  }

  });

  excellent = $('#excellent-progbar');
  vGood = $('#vgood-progbar');
  average = $('#average-progbar');
  poor = $('#poor-progbar');
  terrible = $('#terrible-progbar');
  progBars = [excellent,vGood,average,poor,terrible];





  time = $('#comment-form > div:nth-child(1) > select')
  sort = $('#comment-form > div:nth-child(2) > select');
  var selectors = [time, sort];
  //
  $(selectors).each(function(){
    $(this).change(function(){
      $("#review-content").empty();
      packageCommentForm(1);
    })
  });





});

function packageCommentForm(paginateIndex){
  var position = $(time).prop('selectedIndex');
  var dates = getDateRange(position,"YYYY-MM-DD HH:mm:ss");
  query.from = dates.from;
  query.to = dates.to;
  query.order = $(sort).val();
  getReviewCount(query).done(function(numOfReviews){
    numberOfReviews = numOfReviews;
    if(numOfReviews >0){
      $('#no-reviews').hide();
    $(paginate).bootpag({
        total: Math.ceil(numOfReviews/5)
    });

    submitCommentForm(query);
    getVenueAttributeRating(query);
    getVenuePerformance(query);
  }else{
    $(paginate).bootpag({
        total: 1
    });
    $('#no-reviews').show();
    updateProgBar({terrible: 0, poor: 0, average: 0, v_good: 0, excellent: 0})
  }

  });

}

function submitCommentForm(formData){
  console.log(formData);
  $.ajax({
    url:getBaseURL()+'venues/get_reviews',
    data:formData,
    dataType:'json'
  }).done(function(data){
    console.log(data);
    if(data.length > 0){
      appendReview(data);
    }else{
      $('#no-reviews').show();
    }
  });
}

function getReviewByVenueId(id, num, order){
  $.ajax({
    url:getBaseURL()+'venues/get_reviews',
    data:{id:id,start:num, end:5, order:order},
    dataType:'json'
  }).done(function(data){
    if(data.length > 0){
      appendReview(data);
    }else{
      $('#no-reviews').show();
    }
  });
}
function getReviewCount(query){
  return   $.ajax({
      url:getBaseURL()+'venues/get_count',
      data:{query},
      dataType:'json'
    });
}

 function getVenueAttributeRating(formData){
   $.ajax({
     url:getBaseURL()+'venues/get_venue_attr_rating',
     data:formData,
     dataType:'json'
   }).done(function(data){
     var clean = data[0][0].q1;
     var privacy = data[0][0].q2;
     var room = data[0][0].q3;
     var location = data[0][0].q4;

     updateStarRating(clean,privacy,room,location);


   });
}

function updateStarRating(clean, privacy,room,location){
  $('#cleanliness-rating').rating('rate',clean);
  $('#privacy-rating').rating('rate',privacy);
  $('#rooms-rating').rating('rate',room);
  $('#location-rating').rating('rate',location);
}
function updateProgBar(data){
  $(excellent).width(calcPercentage(data['excellent']));
  $(vGood).width(calcPercentage(data['v_good']));
  $(average).width(calcPercentage(data['average']));
  $(poor).width(calcPercentage(data['poor']));
  $(terrible).width(calcPercentage(data['terrible']));
}
function getVenuePerformance(formData){
  $.ajax({
    url:getBaseURL()+'venues/get_venue_perform',
    data:formData,
    dataType:'json'
  }).done(function(data){
    updateProgBar(data);
    emptyProgBarValues();
    });
}


function calcPercentage(val){
  if(numberOfReviews >0){
    return (val/numberOfReviews)*100;
  }else{
    return 0;
  }

}
function emptyProgBarValues(){
  $(progBars).each(function(index){
    $(progBars[index]).empty();
  })
}

function appendReview(data){
  for(var i =0; i<data.length; i++){
    console.log(data);
    $('.comment-list').append("<header class='text-left' id='comment-header'>"+
    "<div class='comment-user'><i class='fa fa-user'></i> "+ data[i].User.username+"</div>"+
    "<time class='comment-date' datetime='16-12-2014 01:05'><i class='fa fa-clock-o'></i> "+moment(data[i].Review.created).format("dddd, MMMM Do YYYY")+"</time>"+
    "</header>"+
    "<div class='comment-post review-comment'>"+
      "<p>"+data[i].Review.review_text+"</p><div><hr>");
  }
}
