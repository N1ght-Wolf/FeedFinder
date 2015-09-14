<div class="container">
	<?php
		echo $this->Html->script('venue',array('inline' => false));
 ?>
	<div class="page-header">
		<h1>
			<?php echo $venue['Venue']['name']; ?>
		</h1>
		<h4><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
		<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
		<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
		<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
		<span class="glyphicon glyphicon-star" aria-hidden="true"></span></h4>

	</div>
	<div class="row">

		<div class="col-sm-4 col1">
			<a href="#" class="thumbnail">
				<img src="https://irs3.4sqi.net/img/general/500x500/7712209_Zj-jzdOc7wErS0Ng0-WvNtGbsPNMa_nzW-ZXCVJvq_w.jpg" alt="...">
			</a>
			<?php echo $venue['Venue']['address'];  ?>
			<br>
			<?php echo $venue['Venue']['city'];  ?>
			<br>
			<?php echo $venue['Venue']['postalCode'];  ?>
			<br>
			<?php echo $venue['Venue']['country'];  ?>
			<br>

		</div>
		<div class="col-sm-4 col2">

			<h3>Parent ratings</h3>
			<a>Excellent</a>
			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow=<?php echo $ratings['excellent']; ?> aria-valuemin="0" aria-valuemax="100" style=
					<?php echo "width:".$ratings['excellent']."%"; ?>>
						<?php echo $ratings['excellent']; ?>
				</div>
			</div>

			<a href="">Very Good</a>
			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow=<?php echo $ratings['v-good']; ?> aria-valuemin="0" aria-valuemax="100" style=
					<?php echo "width:". $ratings['v-good']."%"; ?>>
						<?php  echo $ratings['v-good']; ?>
				</div>
			</div>

			<a href="#">Average</a>
			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow=<?php  echo $ratings['average']; ?> aria-valuemin="0" aria-valuemax="100" style=
					<?php echo "width:".$ratings['average']."%"; ?>>
						<?php echo $ratings['average']; ?>
				</div>
			</div>
			<a href="#">Poor</a>

			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow=<?php echo $ratings['poor']; ?> aria-valuemin="0" aria-valuemax="100" style=
					<?php echo "width:".$ratings['poor']."%"; ?>>
						<?php echo $ratings['poor']; ?>
				</div>
			</div>

			<a href="#">Terrible</a>
			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow=<?php echo $ratings['terrible']; ?> aria-valuemin="0" aria-valuemax="100" style=
					<?php echo "width:".$ratings['terrible']."%"; ?>>
						<?php echo $ratings['terrible']; ; ?>
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
				<input type="hidden" class="rating" value=<?php echo $average_rating['q1']; ?>/>
			</div>
			<br>
			<div class="">
				Privacy
				<br>

				<input type="hidden" class="rating" value=<?php echo $average_rating['q2']; ?>/>
			</div>
			<br>

			<div class="">
				Rooms
				<br>

				<input type="hidden" class="rating" value=<?php echo $average_rating['q3']; ?>/>
			</div>
			<br>

			<div class="">
				Location
				<br>

				<input type="hidden" class="rating" value=<?php echo $average_rating['q4']; ?>/>
			</div>

		</div>
	</div>
	<div class="row" id='ajax'>

		<div class="col-sm-8 ">
			<h2 class="page-header"><?php echo count($venue['Review']); ?> Reviews from Feed finder Community</h2>
			<div id="review-content" ">

			</div>
		     <div id="page-selection">Pagination goes here</div>

		</div>
	</div>
</div>
