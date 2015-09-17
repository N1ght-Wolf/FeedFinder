<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
      <a class="navbar-brand" href="#">Feed Finder</a>

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
                      $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-home')).' About',
                      array('controller' => 'feedfindertransactions', 'action' => 'index'),
                      array('escape' => FALSE)
                    );
                   ?>
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
                <!-- s<a href='#'><span class="glyphicon glyphicon-envelope"></span> Contact</a> -->
                <?php echo $this->Html->link(
                    $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-envelope')).' Contact',
                    array('controller' => 'feedfindertransactions', 'action' => 'index'),
                    array('escape' => FALSE)
                  );
                 ?>
              </li>

          </ul>
      </div>
  </div><!-- end of container div -->
</nav> <!--  end of nav bar-->
