<?php
echo $this->Html->script('venue', array('inline' => false));
echo $this->Html->script('url.min', array('inline' => false));
echo $this->Html->css('https://rawgit.com/melloc01/angular-input-stars/master/angular-input-stars.css', array('inline' => false));

?>
<div layout="row" layout-xs="column" ng-controller='VenueController' flex xmlns="http://www.w3.org/1999/html">
    <div flex="30" flex-xs="100" id="venue-info">
        <md-content>
            <md-card >
                <div ng-if="venuePhotos[0].filename != null" >
                    <img ng-src="http://cdn.app-movement.com/apps/geolocation/uploads/medium/{{venuePhotos[0].filename}}" class="md-image" alt="Washed Out">

                </div>

                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">{{venueAddress.name}}</span>   <input-stars readonly="readonly" ng-model="venueRating" max="5"></input-stars>
                        <span class="md-subhead">
                            {{venueAddress.city}} </br>
                            {{venueAddress.address}} </br>
                            {{venueAddress.name}} </br>
                            {{venueAddress.postcode}}
                        </span>
                    </md-card-title-text>
                </md-card-title>
<!--                <md-card-content></md-card-content>-->
                <md-card-actions layout="column" layout-align="start">
                    <div layout="row" flex>
                        <div>
                            <md-subheader class="md-no-sticky">From</md-subheader>
                            <md-datepicker ng-model="fromDate" md-placeholder="From Date"></md-datepicker>
                        </div>

                        <div>
                            <md-subheader class="md-no-sticky">To</md-subheader>
                            <md-datepicker ng-model="toDate" md-placeholder="To date"></md-datepicker>
                        </div>
                    </div>
                </md-card-actions>
            </md-card>


        </md-content>
    </div>
    <div flex="70" flex-xs="100" layout-padding id="review">
        <md-toolbar class="md-primary md-hue-1">

            <div class="md-toolbar-tools" layout="row flex">
                <h2> {{venueReviews.length}} Review(s)</h2>
            </div>
        </md-toolbar>
        <md-content class="md-whiteframe-z1" style="height: 600px;">
            <md-list-item class="md-3-line " ng-repeat="review in venueReviews" ng-click="null">
                <ng-letter-avatar data="{{review.User.username}}" avatarborder="true"
                                  shape="round"></ng-letter-avatar>
                <div class="md-list-item-text">
                    <input-stars readonly="readonly" ng-model="review[0].average_rate" max="5"></input-stars>
                    <h3>{{review.User.username}}</h3>
                    <p>{{ review.Review.review_text }}</p>
                    </br>
                </div>
            </md-list-item>
        </md-content>
    </div>
</div>


<style>

</style>