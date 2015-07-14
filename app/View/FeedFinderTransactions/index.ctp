<?php echo $this->Html->script('jquery'); ?>
<?php echo $this->Html->script('jquery-ui'); ?>
<?php echo $this->Html->script('highmaps'); ?>
<?php echo $this->Html->script('graph'); ?>
<?php echo $this->Html->script('map'); ?>
<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->css('feedfinder'); ?>


<div id="graph_div" style="width:50%; height:300px;">

</div>

<div id="geo_div" style="width:50%; height:500px;">

</div>

<div id="pie_div" style="width:50%; height:300px;">

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



  echo $this->Form->end('go');



   ?>

</div>
