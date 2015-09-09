<div id="sidebar" class="sidebar collapsed sidebar-left">
	<!-- Nav tabs -->
	<div class="sidebar-tabs">
		<ul role="tablist" id='list'>
			<li><a href="#home" role="tab" title="This is some text I want to display."><i class="fa fa-bars"></i></a></li>
			<li><a href="#users" role="tab" title="show something about users"><i class="fa fa-users"></i></a></li>
			<li><a href="#review" role="tab" title="show something about reviews"><i class="fa fa-comments"></i></a></li>
			<li><a id='venues-icon' href="#venues" role="tab" title="show something about venues"><i class="fa fa-map-marker"></i></a></li>
		</ul>

		<ul role="tablist">
			<li><a href="#settings" role="tab"><i class="fa fa-gear"></i></a></li>
		</ul>
	</div>

	<!-- Tab panes -->
	<div class="sidebar-content">
		<div class="sidebar-pane" id="home">
			<h1 class="sidebar-header">
				sidebar-v2
				<div class="sidebar-close"><i class="fa fa-caret-left"></i></div>
			</h1>

			<p>A responsive sidebar for mapping libraries like <a href="http://leafletjs.com/">Leaflet</a> or <a href="http://openlayers.org/">OpenLayers</a>.</p>

			<p class="lorem">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata
				sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
				Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>

			<p class="lorem">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata
				sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
				Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>

			<p class="lorem">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata
				sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
				Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>

			<p class="lorem">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata
				sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
				Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
		</div>

		<div class="sidebar-pane" id="users">
			<h1 class="sidebar-header"> Users
				<div class="sidebar-close">
					<i class="fa fa-caret-left"></i>
				</div>
			</h1>
			<?php echo $this->element('form-element');?>
		</div>


				<div class="sidebar-pane" id="review">
					<h1 class="sidebar-header">Review
						<div class="sidebar-close">
							<i class="fa fa-caret-left"></i>
						</div>
					</h1>
					<?php echo $this->element('form-element');?>
				</div>

				<div class="sidebar-pane" id="venues">
					<h1 class="sidebar-header">Venues
						<div class="sidebar-close">
							<i class="fa fa-caret-left"></i>
						</div>
					</h1>
					<?php echo $this->element('form-element');?>
				</div>

				<div class="sidebar-pane" id="settings">
					<h1 class="sidebar-header">Settings
						<div class="sidebar-close"><i class="fa fa-caret-left"></i></div>
					</h1>
				</div>
			</div>
		</div>
