<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK_4F1YBeVbvcr_KCqYEirwi3sD8w2G1Q&callback=initMap" async defer></script>
<?php
// plugins scripts
echo $this->Html->script('url.min', array('inline'=>false));
echo $this->Html->script('en-gb', array('inline' => false));
echo $this->Html->script('moment', array('inline' => false));
echo $this->Html->script('https://rawgit.com/Turbo87/sidebar-v2/master/js/jquery-sidebar.min.js', array('inline' => false));

// my scripts
echo $this->Html->script('dashboard', array('inline' => false));
echo $this->Html->script('google-map', array('inline' => false));

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

                
            </div>


        </div>
    </div>


<div id="map"></div>



