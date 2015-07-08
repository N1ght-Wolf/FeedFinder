<?php echo $this->Html->script('jquery-ui'); ?>
<?php echo $this->Html->script('Chart'); ?>
<?php echo $this->Html->script('feedfinder'); ?>
<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->css('feedfinder'); ?>


<div id="graph_div" style="width:50%; height:500px;">

</div>

<div id="form_div">
  <?php
  echo $this->Form->create(
  array('id' => 'query_form', 'type' => 'GET', 'action'=>'index'));

  // echo $this->Form->input('from',
  //         array(
  //             'id'=>'from_datepicker',
  //            'class' => 'date_form',
  //            'type' => 'text',
  //            'label' => 'from'));
  // echo $this->Form->input('to',
  //         array(
  //           'id'=> 'to_datepicker',
  //            'class' => 'date_form',
  //            'type' => 'text'));


  echo $this->Form->input('date span', array('type' => 'select','label'=>'date span','id'=>'date_span',
  'options'=>$date));



  echo $this->Form->end('go');

   ?>
</div>
