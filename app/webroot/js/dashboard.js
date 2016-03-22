var categories, times, explore;

$(document).ready(function() {
	var sidebar = $('#sidebar').sidebar();
});

feedfinder.controller('sidebarSelectController',function($scope, $http){
	$scope.categories = [
	{name: 'User', model: 'User'},
	{name: 'Venue', model: 'Venue'},
	{name: 'Review', model: 'Review'},
	{name: 'Friendliness', model: 'Review'}
	];

	$scope.times = [
	{name: timeArr[0], range:getDateRange(timeArr[0])}, //today
	{name: timeArr[1], range:getDateRange(timeArr[1])}, //yesterday
	{name: timeArr[2], range:getDateRange(timeArr[2])}, //this week
	{name: timeArr[3], range:getDateRange(timeArr[3])}, //last week
	{name: timeArr[4], range:getDateRange(timeArr[4])}, //this month
	{name: timeArr[5], range:getDateRange(timeArr[5])}, //last month
	{name: timeArr[6], range:getDateRange(timeArr[6])}, //last 3 months
	{name: timeArr[7], range:getDateRange(timeArr[7])}, //last 6 months
	{name: timeArr[8], range:getDateRange(timeArr[8])}, //this year
	{name: timeArr[9], range:getDateRange(timeArr[9])}, //all
	];

	$scope.explore = [
	{name: 'County'},
	{name: 'Super Output Area (UK)'},
	];

	$scope.selectedTime = {name: timeArr[6], range:getDateRange(timeArr[6])},
	$scope.selectedCategory = 	{name: 'Venue', model: 'Venue'};
	$scope.selectedExplore = {name: 'County'};
	/*
		watch all of the select fields, when they change make an ajax request
		[categories,times,explore]
		*/
		$scope.$watchCollection('[selectedCategory.name, selectedTime.name, selectedExplore.name]', function(newValues){
			var selectedCategory = search(newValues[0], $scope.categories);
			var selectedTime = search(newValues[1], $scope.times);		
			var selectedExplore = search(newValues[2], $scope.explore);

			var query = {
				category:selectedCategory,
				time:selectedTime,
				explore:selectedExplore
			}

			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: url()+'/map_query',
				data:query,
				success: function (result){
					deleteMarkers();
					displayMarkers(result);
				},
				error: function (jqXHR, textStatus, errorThrown) {
				}
			});
		});

	});

/*
Loop over an array of objects and return the object that contains the nameKey
*/
function search(nameKey, myArray){
	for (var i=0; i < myArray.length; i++) {
		if (myArray[i].name === nameKey) {
			return myArray[i];
		}
	}
}