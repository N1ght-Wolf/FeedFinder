<h1>Active users</h1>
<table>
    <tr>
        <th>Id</th>
        <th>Review(s)</th>
    </tr>

    <!-- Here is where we loop through our $logs array, printing out log info -->
<?php foreach ($users as $log): ?>
  <tr>
    <td><?php echo $log['FeedFinderTransaction']['user_id']; ?></td>
    <td><?php echo $log['0']['mycount']; ?></td>
  </tr>
<?php endforeach; ?>
<?php unset($log); ?>
</table>
<div class="paginator">
        <?php echo $this->paginator->first(' First ', null, null, array('class' => 'disabled')); ?>
        <?php echo $this->paginator->prev('Previous ', null, null, array('class' => 'disabled')); ?>
        <?php echo $this->paginator->numbers(); ?>
       <?php echo $this->paginator->next(' Next ', null, null, array('class' => 'disabled')); ?>
        <?php echo $this->paginator->last(' Last ', null, null, array('class' => 'disabled')); ?>
</div>
