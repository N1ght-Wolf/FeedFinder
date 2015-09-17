<div class="container">
	<?php
	// print_r( $venue_rating[0]['average_rating']);
		echo $this->Html->script('venue',array('inline' => false));
 ?>
		<div class="page-header">
			<h1>
				<?php echo $venue['Venue']['name']; ?>
			</h1>
			<h4>
				<input type="hidden" class="rating" value=<?php echo $venue_rating[0]['average_rating']; ?>/>
			</h4>
			<?php echo $venue['Venue']['address'];  ?>
			<?php echo $venue['Venue']['city'];  ?>
			<?php echo $venue['Venue']['postalCode'];  ?>
			<?php echo $venue['Venue']['country'];  ?>
		</div>
		<div class="row">

			<div class="col-sm-4 col1">
				<a href="#" class="thumbnail">
					<img src="https://irs3.4sqi.net/img/general/500x500/7712209_Zj-jzdOc7wErS0Ng0-WvNtGbsPNMa_nzW-ZXCVJvq_w.jpg" alt="...">
				</a>

				<br>

			</div>
			<div class="col-sm-4 col2">

				<h3>Parent ratings</h3>
				<a data-star ='5' href='#'>Excellent</a>
				<div class="progress">
  <div id='excellent-progbar'class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
    60%
  </div>
</div>

				<a data-star ='4' href="#">Very Good</a>
				<div class="progress">
	  <div id='vgood-progbar' class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
	    60%
	  </div>
	</div>

				<a data-star ='3' href="#">Average</a>
				<div class="progress">
  <div id='average-progbar' class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
    60%
  </div>
</div>
				<a data-star ='2' href="#">Poor</a>

				<div class="progress">
  <div id='poor-progbar' class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
    60%
  </div>
</div>

				<a data-star ='1' href="#">Terrible</a>
				<div class="progress">
  <div id='terrible-progbar' class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
    60%
  </div>
</div>

			</div>
			<div class="col-sm-4 col3">
				<div>
					<h3>Rating summary</h3>
				</div>
				<div class="">
					Cleanliness
					<br>
					<input id='cleanliness-rating'type="hidden" class="rating" value=value='0'/>
				</div>
				<br>
				<div id='privacy' class="">
					Privacy
					<br>

					<input id='privacy-rating' type="hidden" class="rating" value=value='0'/>
				</div>
				<br>

				<div id='rooms' class="">
					Rooms
					<br>

					<input id='rooms-rating' type="hidden" class="rating" value=value='0'/>
				</div>
				<br>

				<div id='location' class="">
					Location
					<br>

					<input id='location-rating'type="hidden" class="rating" value='0'/>
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
				</h3>
				<hr>
				<div>
					<section class="comment-list" id="review-content">



					</section>
					<div class="alert alert-info" id='no-reviews' style='display:none' role="alert">Unfortunately there has been no review left at this venue</div>

					<div>
					</div>


				</div>

				<div id="page-selection" class="pull-right">
					<!-- Pagination goes here -->
				</div>

			</div>
		</div>
</div>
