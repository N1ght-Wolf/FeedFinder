var feedfinder = angular.module('FeedfinderApp', ['ngMaterial','ngLetterAvatar','jkAngularRatingStars']);
feedfinder.config(function ($httpProvider) {
    // because you did not explicitly state the Content-Type for POST, the default is application/json
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    $httpProvider.defaults.headers.common['Accept'] = 'application/json';
    $httpProvider.defaults.transformRequest = function(data) {
        if (data === undefined) {
            return data;
        }
        //return $.param(data);
        return JSON.stringify(data);
    }
});