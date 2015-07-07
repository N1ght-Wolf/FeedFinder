<?php echo $this->Html->script('jquery-ui'); ?>
<?php echo $this->Html->css('jquery-ui'); ?>



<input type="text" name="date" id="date" />


<?php

echo $this->Form->create('Custom range');
echo $this->Form->input('From');
echo $this->Form->input('To');
echo $this->Form->end('go');
 ?>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#date').datepicker();
    });
</script>
