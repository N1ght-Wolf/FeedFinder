<?php
 echo $this->Html->script('L.TileLayer.BetterWMS',array('inline' => false));
 echo $this->Html->script('styledLayerControl',array('inline' => false));
 echo $this->Html->script('L.Control.Locate.min.js',array('inline' => false));
 echo $this->Html->script('moment',array('inline' => false));
 echo $this->Html->script('en-gb',array('inline' => false));
 echo $this->Html->script('spin.min');
 echo $this->Html->script('leaflet-sidebar');
 echo $this->Html->script('Control.Geocoder');

 echo $this->Html->script('leaflet-map');
 echo $this->Html->script('stats');




 echo $this->Html->script('leaflet.spin',array('inline' => false));



 echo $this->Html->css('stats');
 echo $this->Html->css('L.Control.Locate.min');
 echo $this->Html->css('Control.Geocoder');
 echo $this->Html->css('leaflet-sidebar');


?>
  <?php echo $this->element('map-element');?>
