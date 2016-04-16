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
    $scope.fromDate = new Date("July 21, 1983 01:15:00");
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
            $scope.venueAddress = result.venue_address.Venue;
            $scope.venueReviews = result.venue_reviews;
            $scope.secondRate = 3;
            console.log($scope.venueReviews);
        }).error(function (data, status, headers, config) {
            $scope.status = status;
        });
    });

});

function getVenueInfo() {

}