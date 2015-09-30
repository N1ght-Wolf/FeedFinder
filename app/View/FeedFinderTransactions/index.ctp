<?php
echo $this->Html->css(
'https://rawgit.com/peachananr/onepage-scroll/master/onepage-scroll.css'
);
echo $this->Html->script(
'https://rawgit.com/peachananr/onepage-scroll/master/jquery.onepage-scroll.min.js',
array('inline' => false)
);
echo $this->Html->css(
'index'
);
print Configure::version();
 ?>
	<!--  show the navbar element -->


	<!-- fetch and place the carousel-element -->
	<?php //echo $this->element('carousel-element'); ?>
		<div class="main">
			<section class='page1'>
				<div class="page_container">
					<?php echo $this->Html->image('https://www.mapbox.com/home/slides/2@2x.png', array('alt' => 'CakePHP','class'=>'img-responsive')); ?>
						<h1 class="white-text">Discover Venues</h1>
						<h5 class="white-text">
							Find highly reviewed, suitable breastfeeding locations on a handy map,
							<br> you'll always know the most breastfeeding friendly places in town
						</h5>
						<a class="ghost-button" href="#">How it works</a>

						<a class="ghost-button" href="#">Try it out</a>

				</div>
			</section>

			<section class='page2'>

				<div class="page_container">

					<div class="row white-text">
            <div class="col-lg-12">
                <h2 class=" text-center">Features</h2>
            </div>
            <?php echo $this->Html->image('map-stat.png', array('alt' => 'CakePHP','class'=>'center-block img-rounded img-responsive','height'=>800,'width'=>800)); ?>



						<div class="col-lg-4">
							<h4><i class="fa fa-search fa-1x" style="text-align:center"></i> Discover
							</h4>
							<p>
								A customer requests a delivery at an address, which our geocoding API transforms into coordinates.
							</p>
						</div>
						<!-- /.col-lg-4 -->

            <div class="col-lg-4">
							<h4><i class="fa fa-location-arrow fa-1x" style="text-align:center"></i> Discover
							</h4>
							<p>
								A customer requests a delivery at an address, which our geocoding API transforms into coordinates.
							</p>
						</div>
						<!-- /.col-lg-4 -->

            <div class="col-lg-4">
              <h4><i class="fa fa-location-arrow fa-1x" style="text-align:center"></i> Discover
              </h4>
              <p>
                A customer requests a delivery at an address, which our geocoding API transforms into coordinates.
              </p>
            </div>
            <!-- /.col-lg-4 -->
					</div>
				</div>

			</section>

			<section id='section-three'>

			</section>

		</div>










		<script type="text/javascript">
			$(".main").onepage_scroll({
				sectionContainer: "section", // sectionContainer accepts any kind of selector in case you don't want to use section
				easing: "ease", // Easing options accepts the CSS3 easing animation such "ease", "linear", "ease-in",
				// "ease-out", "ease-in-out", or even cubic bezier value such as "cubic-bezier(0.175, 0.885, 0.420, 1.310)"
				animationTime: 1000, // AnimationTime let you define how long each section takes to animate
				pagination: true, // You can either show or hide the pagination. Toggle true for show, false for hide.
				updateURL: false, // Toggle this true if you want the URL to be updated automatically when the user scroll to each page.
				beforeMove: function(index) {}, // This option accepts a callback function. The function will be called before the page moves.
				afterMove: function(index) {}, // This option accepts a callback function. The function will be called after the page moves.
				loop: false, // You can have the page loop back to the top/bottom when the user navigates at up/down on the first/last page.
				keyboard: true, // You can activate the keyboard controls
				responsiveFallback: false, // You can fallback to normal page scroll by defining the width of the browser in which
				// you want the responsive fallback to be triggered. For example, set this to 600 and whenever
				// the browser's width is less than 600, the fallback will kick in.
				direction: "vertical" // You can now define the direction of the One Page Scroll animation. Options available are "vertical" and "horizontal". The default value is "vertical".
			});
		</script>
