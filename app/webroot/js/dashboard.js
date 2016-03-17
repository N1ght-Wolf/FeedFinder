$(document).ready(function() {
	var sidebar = $('#sidebar').sidebar();
});

feedfinder.controller('sidebarSelectController',function($scope){

	$scope.categories = [
	{id: 1, name: 'User'},
	{id: 2, name: 'Venue'},
	{id: 3, name: 'Review'},
	{id: 4, name: 'Friendliness'}
	];
	$scope.times = [
	{id: 1, name: 'Today'},
	{id: 2, name: 'This Week'},
	{id: 3, name: 'This Month'},
	{id: 4, name: 'Last 3 Months'},
	{id: 5, name: 'Last 6 Months'},
	{id: 6, name: 'This year'},
	{id: 7, name: 'All'}
	];
	$scope.explore = [
	{id: 1, name: 'County'},
	{id: 2, name: 'Super Output Area (UK)'},
	];

	$scope.selectedTime = {id: 2, name: 'This Week'};
	$scope.selectedCategory = {id: 1, name: 'User'};
	$scope.selectedExplore = {id: 1, name: 'County'};
	/*
		watch all of the select fields, when they change make an ajax request
	*/
	$scope.$watchCollection('[selectedCategory.name, selectedTime.name, selectedExplore]', function(newValues){

	});

});

feedfinder.directive("formOnChange", function($parse){
	return {
		require: "form",
		link: function(scope, element, attrs){
			var cb = $parse(attrs.formOnChange);
			element.on("change", function(){
				cb(scope);
			});
		}
	}
});