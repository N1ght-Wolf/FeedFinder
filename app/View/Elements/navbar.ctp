<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
      <a class="navbar-brand" href="#">Feed Finder
      </a>

      <button class='navbar-toggle' data-toggle='collapse' data-target='.navHeaderCollapse'>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
      </button>

      <div class='collapse navbar-collapse nav-reponsive-collapse'>
            <ul class='nav navbar-nav pull-right'>
              <li class='active'>
                  <!-- <a href='#'><span class="glyphicon glyphicon-home"></span> Home</a> -->
                  <?php echo $this->Html->link(
                      $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-home')).' Home',
                      array('controller' => 'feedfindertransactions', 'action' => 'index'),
                      array('escape' => FALSE)
                    );
                   ?>
              </li>

              <li>
                <?php echo $this->Html->link(
                    $this->Html->tag('span', '', array('class' => 'fa fa-info')).' About',
                    array('controller' => 'feedfindertransactions', 'action' => 'about'),
                    array('escape' => FALSE)
                  );
                 ?>
                <!-- <a href='../stats'><span class="glyphicon glyphicon-stats"></span> Stats</a> -->
              </li>


              <li>
                <?php echo $this->Html->link(
                    $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-stats')).' Explore',
                    array('controller' => 'feedfindertransactions', 'action' => 'stats'),
                    array('escape' => FALSE)
                  );
                 ?>
                <!-- <a href='../stats'><span class="glyphicon glyphicon-stats"></span> Stats</a> -->
              </li>

              <li>
                <a href='#' data-toggle="modal" data-target="#contact-modal">
                  <span class="glyphicon glyphicon-envelope"></span> Contact
                </a>

              </li>

          </ul>
      </div>
  </div><!-- end of container div -->
</nav> <!--  end of nav bar-->

<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="contactLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="panel panel-primary">
                 <div class="panel-heading">
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                     <h4 class="panel-title" id="contactLabel"><span class="glyphicon glyphicon-info-sign"></span> Any questions? Feel free to contact us.</h4>
                 </div>
                 <div class="modal-body" style="padding: 5px;">
                   <form role="form" id="contact-form" class="contact-form">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="text" class="form-control" name="Name" autocomplete="off" id="name" placeholder="Name">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="email" class="form-control" name="email" autocomplete="off" id="email" placeholder="E-mail">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <textarea class="form-control textarea" rows="3" name="Message" id="Message" placeholder="Message"></textarea>
                        </div>
                      </div>
                    </div>
                     </div>
                     <div class="panel-footer" style="margin-bottom:-14px;">
                       <div class="row">
                         <div class="col-md-12">
                           <button type="submit" class="btn main-btn pull-right">Send a message</button>
                         </div>
                       </div>
                     </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
