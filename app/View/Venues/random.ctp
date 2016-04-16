<?php
echo $this->Html->script('venue', array('inline' => false));
echo $this->Html->script('url.min', array('inline' => false));

?>
<md-content layout-align="layout-padding layout-margin layout-fill style">

    <div layout="column" ng-controller='VenueController' flex="70" layout-align="center center">
        <!--    statistics of the venue div-->
        <div flex="70">
            <md-card>
                <img ng-src="http://i.imgur.com/XQRoQYN.jpg?1" class="md-image" alt="Washed Out">
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">{{venueAddress.name}}</span>
                        <span class="md-subhead">
                            {{venueAddress.city}} </br>
                            {{venueAddress.address}} </br>
                        </span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content></md-card-content>
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
        </div>


        <md-content style="height: 600px;">
            <md-subheader class="md-primary ">Reviews</md-subheader>
            <section class="md-whiteframe-z1">
                <md-list-item class="md-3-line " ng-repeat="review in venueReviews" ng-click="null">
                    <ng-letter-avatar height="60" width="60" data="{{review.User.username}}" avatarborder="true"
                                      shape="round"></ng-letter-avatar>
                    <div class="md-list-item-text">
                        <!--                                <b>{{review.User.username}}</b>-->
                        <jk-rating-stars class="my-custom-stars" rating="review.Review.average_rating"
                                         read-only="readOnly"></jk-rating-stars>
                        <p>{{ review.Review.review_text }}
                        <p>
                    </div>
                </md-list-item>
            </section>
        </md-content>

    </div>
</md-content>



<style>

    .my-custom-stars .button .material-icons {
        font-size: 15px;
    }

    /*.my-custom-stars .star-button.star-on .material-icons {*/
    /*color: #003399;*/
    /*}*/

    /*.my-custom-stars .star-button.star-off .material-icons {*/
    /*color: #99ccff;*/
    /*}*/
</style>