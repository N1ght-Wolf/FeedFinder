<?php
 echo $this->Html->script('https://cdn.rawgit.com/rclark/6908938/raw/7a41a88ac84a6a5595b6b6ecebbadfd2d7f7df5e/L.TileLayer.BetterWMS.js',array('inline' => false));
 echo $this->Html->script('https://cdn.rawgit.com/davicustodio/Leaflet.StyledLayerControl/master/src/styledLayerControl.js',array('inline' => false));
 echo $this->Html->script('https://cdn.rawgit.com/domoritz/leaflet-locatecontrol/gh-pages/src/L.Control.Locate.js',array('inline' => false));
 echo $this->Html->script('https://cdn.rawgit.com/moment/moment/develop/moment.js',array('inline' => false));
 echo $this->Html->script('https://cdn.rawgit.com/moment/moment/develop/src/locale/en-gb.js',array('inline' => false));
 echo $this->Html->script('https://cdn.rawgit.com/makinacorpus/Leaflet.Spin/master/leaflet.spin.js',array('inline' => false));
 echo $this->Html->script('https://cdn.rawgit.com/fgnass/spin.js/master/spin.min.js',array('inline' => false));
 echo $this->Html->script('leaflet-sidebar',array('inline' => false));
 echo $this->Html->script('Control.Geocoder',array('inline' => false));
 echo $this->Html->script('leaflet-map',array('inline' => false));
 echo $this->Html->script('stats',array('inline' => false));
 echo $this->Html->css('Control.Geocoder',array('inline' => false));
 echo $this->Html->css('leaflet-sidebar',array('inline' => false));
 echo $this->Html->css('stats');






?>
  <?php echo $this->element('map-element');?>
