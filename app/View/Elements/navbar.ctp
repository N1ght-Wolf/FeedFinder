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
              <li>
                  <?php echo $this->Html->link(
                      $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-home')).' Home',
                      array('controller' => 'pages', 'action' => 'index'),
                      array('escape' => FALSE)
                    );
                   ?>
              </li>


              <li>
                <?php echo $this->Html->link(
                    $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-stats')).' Explore',
                    array('controller' => 'dashboards', 'action' => 'index'),
                    array('escape' => FALSE)
                  );
                 ?>
              </li>

              <li>
                <?php echo $this->Html->link(
                    $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-envelope')).' Contact Us',
                    array('controller' => 'pages', 'action' => 'index/#2'),
                    array('escape' => FALSE)
                  );
                 ?>
              </li>

          </ul>

        <!--  <form class="navbar-form navbar-right" role="search">
              <div class="form-group">
                  <input type="text" class="form-control" placeholder="Search">
              </div>
              <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
          </form> -->
      </div>




  </div>
</nav>

     </div>
