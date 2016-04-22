var query = {
    id: 0,
    from: '',
    to: ''
};
var fromDate, toDate;


$(document).ready(function () {
    //grab the venue id from url
    getVenueInfo();


});

feedfinder.controller('VenueController', function ($scope, $http) {
    query.id = $.url('?id');
    $scope.query = query;
    $scope.fromDate = new Date("January 1, 2013 00:00:00");
    $scope.toDate = new Date();
    $scope.query.from = $scope.fromDate.toISOString().substring(0, 19).replace('T', ' ');
    $scope.query.to = $scope.toDate.toISOString().substring(0, 19).replace('T', ' ');
   

    /*Watching the dates*/
    $scope.$watchCollection('[fromDate, toDate]', function (newValues) {

        $scope.query.from = newValues[0].toISOString().substring(0, 19).replace('T', ' ');
        $scope.query.to = newValues[1].toISOString().substring(0, 19).replace('T', ' ');
        //console.log($scope.query);
        $http({
            method: "GET",
            url: url('path') + '/venue_info',
            params: $scope.query
        }).success(function (result, status, headers, config) {
            result.venue_reviews[0].Review.q1 = 5;
            $scope.venueAddress = result.venue_address.Venue;
            $scope.venueReviews = result.venue_reviews;
            $scope.secondRate = 3;
            console.log($scope.venueReviews[0].Review.q1);
        }).error(function (data, status, headers, config) {
            $scope.status = status;
        });
    });

});

function getVenueInfo() {

}