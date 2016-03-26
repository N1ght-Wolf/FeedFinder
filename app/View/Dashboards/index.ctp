<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK_4F1YBeVbvcr_KCqYEirwi3sD8w2G1Q&callback=initMap" async defer></script>
<?php
// plugins scripts
echo $this->Html->script('url.min', array('inline'=>false));
echo $this->Html->script('daterange', array('inline'=>false));
echo $this->Html->script('en-gb', array('inline' => false));
echo $this->Html->script('moment', array('inline' => false));
echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js', array('inline' => false));
echo $this->Html->script('https://rawgit.com/Turbo87/sidebar-v2/master/js/jquery-sidebar.min.js', array('inline' => false));

// my scripts
echo $this->Html->script('dashboard', array('inline' => false));
echo $this->Html->script('google-map', array('inline' => false));
echo $this->Html->script('markerclusterer', array('inline'=>false));

echo $this->Html->css('dashboard');
echo $this->Html->css('https://rawgit.com/Turbo87/sidebar-v2/master/css/gmaps-sidebar.css');

?>

<div id="sidebar" class="sidebar collapsed">
    <!-- Nav tabs -->
    <div class="sidebar-tabs">
        <ul role="tablist">
            <li><a href="#home" role="tab"><i class="fa fa-bars"></i></a></li>
        </ul>
    </div>

    <!-- Tab panes -->
    <div class="sidebar-content">
        <div class="sidebar-pane" id="home">
            <h1 class="sidebar-header">
                Control
                <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
            </h1>

    <form name="myForm" ng-controller="sidebarSelectController" form-on-change="change()">

        <div>
        <md-input-container class="md-block">
            <label>Category</label>
            <md-select ng-model="selectedCategory.name">
            <md-option ng-value="category.name" ng-repeat="category in categories">{{ category.name }}</md-option>
            </md-select>
        </md-input-container>

        <md-input-container class="md-block">
            <label>Time</label>
            <md-select ng-model="selectedTime.name">
            <md-option ng-value="time.name" ng-repeat="time in times" style="z-index:2001px;">{{ time.name }}</md-option>
            </md-select>
        </md-input-container>

        <md-input-container class="md-block">
            <label>Explore</label>
            <md-select ng-model="selectedExplore.name">
            <md-option ng-value="ex.name" ng-repeat="ex in explore">{{ ex.name }}</md-option>
            </md-select>
        </md-input-container>
        </div>
    </form>


</div>


</div>
</div>


<div id="map"></div>



