<?php echo $this->Html->script('jquery'); ?>
<?php echo $this->Html->script('jquery-ui'); ?>
<?php echo $this->Html->script('graph'); ?>
<?php echo $this->Html->script('leaflet-map'); ?>
<?php echo $this->Html->script('highmaps'); ?>
<?php echo $this->Html->script('leaflet'); ?>
<?php echo $this->Html->script('leaflet-pip'); ?>
<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->css('feedfinder'); ?>
<?php echo $this->Html->css('leaflet'); ?>
<?php echo $this->Html->script('world.min'); ?>
<?php echo $this->Html->script('GBR.min'); ?>
<?php echo $this->Html->script('BRB.min'); ?>










<div id="graph_div" style="width:50%; height:300px;">

</div>
<input id="location" class="controls" type="text"
      placeholder="Enter a location">
<!-- <div id="map-canvas" style="width:50%; height:500px;"></div>
<div id="panel">
      <button onclick="toggleHeatmap()">Toggle Heatmap</button>
      <button onclick="changeRadius()">Change radius</button>
</div> -->

<div id="map" style="width:40%; height:560px;">

</div>
<div id = 'basic_counts'>

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
