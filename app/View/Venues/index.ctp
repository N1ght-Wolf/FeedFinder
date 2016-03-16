<?php
echo $this->Html->script('en-gb', array('inline' => false));
echo $this->Html->script('bootstrap-rating.min', array('inline' => false));
echo $this->Html->script('jquery.bootpag.min', array('inline' => false));
echo $this->Html->script('moment', array('inline' => false));
echo $this->Html->script('daterange', array('inline' => false));


?>
<div class="container">
    <?php
    // print_r( $venue_rating[0]['average_rating']);
    echo $this->Html->script('venue', array('inline' => false));
    ?>
    <div class="page-header">
        <h1>
            <?php echo $venue['Venue']['name']; ?>
        </h1>
        <h4>
            <input type="hidden" class="rating" value=<?php echo $venue_rating[0]['average_rating']; ?>/>
        </h4>
        <?php echo $venue['Venue']['address']; ?>
        <?php echo $venue['Venue']['city']; ?>
        <?php echo $venue['Venue']['postcode']; ?>
        <?php echo $venue['Venue']['country']; ?>
    </div>
    <div class="row">

        <div class="col-sm-4 col1">
            <a href="#" class="thumbnail">
                <img
                    src="https://irs3.4sqi.net/img/general/500x500/7712209_Zj-jzdOc7wErS0Ng0-WvNtGbsPNMa_nzW-ZXCVJvq_w.jpg"
                    alt="...">
            </a>

            <br>

        </div>
        <div class="col-sm-4 col2">

            <h3>Parent ratings</h3>
            <a href=<?php echo Router::url($this->here, true) . '?rating_low=4&rating_high=5'; ?> id='excellent-link'
               class='performance-review-link'>Excellent</a>


            <div class="progress">
                <div id='excellent-progbar' class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                     aria-valuemax="100" style="width: 60%;">

                </div>
            </div>

            <a href=<?php echo Router::url($this->here, true) . '?rating_low=3&rating_high=4'; ?> id='vgood-link'class =
            '
            performance-review-link'>Very Good</a>
            <div class="progress">
                <div id='vgood-progbar' class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                     aria-valuemax="100" style="width: 60%;">

                </div>
            </div>

            <a href=<?php echo Router::url($this->here, true) . '?rating_low=2&rating_high=3'; ?> id='average-link'class
            = 'performance-review-link'>Average</a>
            <div class="progress">
                <div id='average-progbar' class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                     aria-valuemax="100" style="width: 60%;">

                </div>
            </div>
            <a href=<?php echo Router::url($this->here, true) . '?rating_low=1&rating_high=2'; ?> id='poor-link'
               class='performance-review-link'>Poor</a>

            <div class="progress">
                <div id='poor-progbar' class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                     aria-valuemax="100" style="width: 60%;">

                </div>
            </div>

            <a href=<?php echo Router::url($this->here, true) . '?rating_low=0&rating_high=1'; ?> id='terrible-link'
               class='performance-review-link'>Terrible</a>

            <div class="progress">
                <div id='terrible-progbar' class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                     aria-valuemax="100" style="width: 60%;">

                </div>
            </div>

        </div>
        <div class="col-sm-4 col3">
            <div>
                <h3>Rating summary</h3>
            </div>
            <div class="">
                Comfort
                <br>
                <input id='comfort-rating' type="hidden" class="rating" value=value='0'/>
            </div>
            <br>

            <div class="">
                Cleanliness
                <br>

                <input id='cleanliness-rating' type="hidden" class="rating" value=value='0'/>
            </div>
            <br>

            <div class="">
                Privacy
                <br>

                <input id='privacy-rating' type="hidden" class="rating" value=value='0'/>
            </div>
            <br>

            <div class="">
                Baby facilities
                <br>

                <input id='baby-fac-rating' type="hidden" class="rating" value='0'/>
            </div>
            <div class="">
                Average spend
                <br>

                <input id='average-spend-rating' type="hidden" class="rating" value='0'/>
            </div>

        </div>
    </div>
    <div class="row">

        <div class="col-lg-10">
            <form id='comment-form' class="form-inline pull-right" role="form">
                <div class="form-group">
                    <label for="sel1">Time span:</label>
                    <select class="form-control" name='form-timespan'>
                        <option value='2015-06-01'>Today</option>
                        <option>Yesterday</option>
                        <option>This week</option>
                        <option>Last week</option>
                        <option>This month</option>
                        <option>Last month</option>
                        <option>Last 3 month</option>
                        <option>Last 6 month</option>
                        <option>This year
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                        </option>
                        <option>Beginning of time</option>
                        <option></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sel1">sort by:</label>
                    <select class="form-control" name='form-sort'>
                        <option value='DESC'>Newest</option>
                        <option value='ASC'>Oldest</option>
                    </select>
                </div>
            </form>
            <h3 class="" id='review-count'>
                23 reviews
            </h3>
            <hr>
            <div>
                <section class="comment-list" id="review-content">


                </section>
                <div class="alert alert-info" id='no-reviews' style='display:none' role="alert">
                    Unfortunately there has been no review left.
                    Toggle the search drop down to adjust your search <br>OR
                    click the <i class="fa fa-times-circle " style="color:red;"></i>
                    to remove filter.
                </div>

                <div>
                </div>


            </div>

            <div id="page-selection" class="pull-right">
                <!-- Pagination goes here -->
            </div>

        </div>
    </div>
</div>
