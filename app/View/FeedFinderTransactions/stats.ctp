<?php echo $this->element('navbar');
?>

<?php echo $this->Html->css('stats');
 echo $this->Html->css('https://rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/css/bootstrap-datetimepicker.min.css');
 echo $this->Html->script('https://rawgit.com/moment/moment/develop/moment.js');
 echo $this->Html->script('https://rawgit.com/moment/moment/master/locale/en-gb.js');
 echo $this->Html->script('https://rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/js/bootstrap-datetimepicker.min.js');
 echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/css/bootstrap-select.min.css');
 echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/js/bootstrap-select.min.js');

?>
<div class="container">

  <div id="sidebar" class="sidebar sidebar-left collapsed">
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
                      Interesting header goes here
                      <div class="sidebar-close"><i class="fa fa-caret-left"></i></div>
                  </h1>

                  <form id='stats-control' role="form" action='stats_submit'>
                    <div class="form-group">
                      <label for="from">From:</label>
                        <div class='input-group' id='datetimepicker1'>
                            <input type='text' class="form-control" name="from-date" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="to">To:</label>
                        <div class='input-group' id='datetimepicker2'>
                            <input type='text' class="form-control" name="to-date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="attribute">Action</label>
                        <select class="form-control" name="action">
                          <option>Review</option>
                          <option>Venues</option>
                          <option>Users</option>
                        </select>
                    </div>

                      <button type="submit" class="btn btn-default"  >Submit</button>
                  </form>
              </div>

          </div>
      </div>



  <div id="map" class="sidebar-map">

  </div>
  <?php echo $this->element('graph');?>

</div>

</div>
<!-- end map -->
