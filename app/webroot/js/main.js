var feedfinder = angular.module('FeedfinderApp', ['ngMaterial','ngLetterAvatar','angular-input-stars']);
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

feedfinder.controller('navbarController', function ($scope, $mdDialog, $mdMedia) {
    $scope.status = '  ';
    $scope.customFullscreen = $mdMedia('xs') || $mdMedia('sm');
    $scope.showAlert = function(ev) {
        // Appending dialog to document.body to cover sidenav in docs app
        // Modal dialogs should fully cover application
        // to prevent interaction outside of dialog
        console.log(ev);
        $mdDialog.show(
            $mdDialog.alert()
                .parent(angular.element(document.querySelector('#map')))
                .clickOutsideToClose(true)
                .title('Contact Us')
                .textContent("Get in touch by emailing madeline.balaam@newcastle.ac.uk")
                .ariaLabel('Alert Dialog Demo')
                .ok('Got it!')
                .targetEvent(ev)
        );
    };
});
//https://github.com/melloc01/angular-input-stars