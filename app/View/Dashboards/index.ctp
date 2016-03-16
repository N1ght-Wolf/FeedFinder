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

<div id="map"></div>



