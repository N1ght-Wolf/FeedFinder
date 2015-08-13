<?php echo $this->Html->script('jquery'); ?>
<?php echo $this->Html->script('jquery-ui'); ?>
<?php echo $this->Html->script('graph'); ?>
<?php echo $this->Html->script('iso.js'); ?>
<?php echo $this->Html->script('leaflet-map'); ?>
<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->css('feedfinder'); ?>
<?php echo $this->Html->script('highmaps'); ?>

<link href='https://api.mapbox.com/mapbox.js/v2.2.1/mapbox.css' rel='stylesheet' />

<div id="graph_div" style="width:50%; height:300px;">

</div>


<div id="map" style="width:40%; height:560px;">

</div>





<div id="form_div">
  <?php
  echo $this->Form->create(array('id' => 'query_form', 'type' => 'GET'));

  echo $this->Form->input('timespan', array('type' => 'select','label'=>'timespan','id'=>'timespan',
  'options'=>$timespan_options));

  echo $this->Form->input('actions', array('type' => 'select','label'=>'actions','id'=>'actions',
  'options'=>$actions,'default'=>2));

   echo $this->Form->input('Location', array('type' => 'text','label'=>'location','id'=>'location-input'));


  echo $this->Form->end('go');



   ?>

</div>
