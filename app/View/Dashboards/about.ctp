<?php echo $this->element('navbar'); ?>
<div class="container">

        <!-- Introduction Row -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-center">About Us

                </h1>
                <p>
                  We’re a team of researchers at Open Lab, Newcastle University who designed and built FeedFinder as part of a research project.
                   FeedFinder is a mobile application that was designed and developed with the help of breastfeeding women in the North East of England.
                   The app is free to download on Android and iOS and enables women (and other interested parties,
                   such as breastfeeding community workers, midwives, partners and business owners) to explore and contribute to a map which describes how supportive the local community and services are toward women who breastfeed.
                    Women can use FeedFinder to search for and view places on the map where other women have previously breastfed, along with those women’s reviews and ratings along five categories: Comfy(ness), Clean(liness), Privacy, Baby Facilities and Average Spend. Women can also add new places to the map where they have breastfed and leave reviews for that place.

                  The app was designed to help create a supportive environment in which women feel comfortable to breastfeed outside of the home.

                  This dashboard enables the data collected from FeedFinder (reviews, venues added and number of new users) to be easily accessible.
                   Using the explore tab, a map can be used to search for newly added venues, latest reviews, best reviews and number of new downloads of FeedFinder in an area.
                    This data can be used to track the support of the local community in supporting breastfeeding overtime and identify areas that may require an intervention or
                    further support from the breastfeeding community on how to improve their practice.
                </p>
            </div>
        </div>

        <!-- Team Members Row -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header text-center">Team</h2>
            </div>
            <div class="col-lg-4 col-sm-6 text-center">
              <?php echo $this->Html->image('madeline.jpeg', array('alt' => 'CakePHP', 'height'=>200, 'width'=>200, 'class'=>'img-circle img-responsive img-center')); ?>
                <h3>Madeline Balaam
                    <small>Lecturer</small>
                </h3>
                <p>My work focuses on designing for digital health and wellbeing, with a particular focus on digital public health</p>
            </div>
            <div class="col-lg-4 col-sm-6 text-center">
              <?php echo $this->Html->image('emma.jpeg', array('alt' => 'CakePHP', 'height'=>200, 'width'=>200, 'class'=>'img-circle img-responsive img-center')); ?>
                <h3>Emma Simpson
                    <small>Doctoral Trainee</small>
                </h3>
                <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>
            </div>
            <div class="col-lg-4 col-sm-6 text-center">
              <?php echo $this->Html->image('andy.jpeg', array('alt' => 'CakePHP', 'height'=>200, 'width'=>200, 'class'=>'img-circle img-responsive img-center')); ?>
                <h3>Andrew Garbett
                    <small>Doctoral Trainee</small>
                </h3>
                <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>
            </div>
            <div class="col-lg-4 col-sm-6 text-center">
              <?php echo $this->Html->image('ed.jpeg', array('alt' => 'CakePHP', 'height'=>200, 'width'=>200, 'class'=>'img-circle img-responsive img-center')); ?>
                <h3>Edward Jenkins
                    <small>Research Associate</small>
                </h3>
                <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>
            </div>
          

        </div>

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright © Your Website <script>document.write(new Date().getFullYear())</script></p>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </footer>

    </div>
