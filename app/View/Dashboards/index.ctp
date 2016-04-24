<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK_4F1YBeVbvcr_KCqYEirwi3sD8w2G1Q&libraries=places&callback=initMap" async defer></script>
<?php
// plugins scripts
echo $this->Html->script('url.min', array('inline'=>false));
echo $this->Html->script('daterange', array('inline'=>false));
echo $this->Html->script('sld', array('inline'=>false));
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
<!--<div layout="row"  style="position: absolute; z-index: 99; background-color: #ffffff;" >-->
<!--    <md-input-container>-->
<!--        <label>Items</label>-->
<!--        <md-select ng-model="selectedItem" md-selected-text="getSelectedText()">-->
<!--            <md-optgroup label="items">-->
<!--                <md-option ng-value="item" ng-repeat="item in items">Item {{item}}</md-option>-->
<!--            </md-optgroup>-->
<!--        </md-select>-->
<!--    </md-input-container>-->
<!--</div>-->
<div id="sidebar" class="sidebar collapsed" id="prog-element">
    <div class="sidebar-tabs">
        <ul role="tablist">
            <li><a href="#home" role="tab"><i class="fa fa-bars"></i></a></li>
        </ul>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-pane" id="home">
            <h1 class="sidebar-header">
                Control
                <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
            </h1>

    <form name="myForm" ng-controller="sidebarSelectController" layout="column">

          <md-content layout-padding>
        <md-input-container class="md-block">
            <label>Category</label>
            <md-select ng-model="selectedCategory.name">
            <md-option ng-value="category.name" ng-repeat="category in categories">{{ category.name }}</md-option>
            </md-select>
        </md-input-container>
                </md-content>
                          <md-content layout-padding>
        <md-input-container class="md-block">
            <label>Time</label>
            <md-select ng-model="selectedTime.name">
            <md-option ng-value="time.name" ng-repeat="time in times" >{{ time.name }}</md-option>
            </md-select>
        </md-input-container>
        </md-content>
                  <md-content layout-padding>

        <md-input-container class="md-block">
            <label>Explore</label>
            <md-select ng-model="selectedExplore.name">
            <md-option ng-value="ex.name" ng-repeat="ex in explore">{{ ex.name }}</md-option>
            </md-select>
        </md-input-container>
        </md-content>
    </form>


</div>


</div>
</div>


<!-- <md-progress-circular md-mode="indeterminate" md-diameter="90" id='prog-element' ></md-progress-circular> -->

<input id="pac-input" class="controls" type="text" placeholder="Search Box">
<div id="legend">
    My first legend!
</div>
<div id="map">
</div>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    #map {
        height: 100%;
    }
    .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }

    .pac-container {
        font-family: Roboto;
    }

    #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
    }

    #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }
    #target {
        width: 345px;
    }
</style>


