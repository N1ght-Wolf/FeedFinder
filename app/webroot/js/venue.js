var paginate;
$(document).ready(function() {

  getReviewCount().done(function(numOfReviews){

    paginate = $('#page-selection').bootpag({
        total: (numOfReviews/5)
    });
    getReviewByVenueId($.url('3'),1);


    paginate.on("page", function(event, num){
      event.preventDefault();
        getReviewByVenueId($.url('3'),num);
        $("#review-content").html("Page " + num); // or some ajax content loading...

      // console.log($.url('3'));
      // $.ajax({
      //   url:getBaseURL()+'venues/get_reviews',
      //   data:{id:$.url('3'),start:num, end:5},
      //   dataType:'json'
      // }).done(function(data){
      //   appendReview(data);
      // });

    });

  });

});
function getReviewByVenueId(id, num){
  $.ajax({
    url:getBaseURL()+'venues/get_reviews',
    data:{id:id,start:num, end:5},
    dataType:'json'
  }).done(function(data){
    appendReview(data);
  });
}
function getReviewCount(){
  return   $.ajax({
      url:getBaseURL()+'venues/get_count',
      data:{id:$.url('3')},
      dataType:'json'
    });
}

function appendReview(data){
  for(var i =0; i<data.length; i++){
    $('#review-content').append(

      "<div class='media well well-lg'><div class='media-left'>"+
      "<a href='#'>"+
        "<img class='media-object' data-src='holder.js/64x64' alt='64x64' src='data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PCEtLQpTb3VyY2UgVVJMOiBob2xkZXIuanMvNjR4NjQKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNGZjMGZkYTk2NSB0ZXh0IHsgZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE0ZmMwZmRhOTY1Ij48cmVjdCB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSIxMy40Njg3NSIgeT0iMzYuNSI+NjR4NjQ8L3RleHQ+PC9nPjwvZz48L3N2Zz4=' data-holder-rendered='true' style='width: 64px; height: 64px;'>"+
      "</a>"+
    "</div>"+
    "<div class='media-body'>"+
      "<h4 class='media-heading'>"+data[i].User.username+"</h4>"
      +data[i].Review.review_text+
    "</div></div");
  }
}
