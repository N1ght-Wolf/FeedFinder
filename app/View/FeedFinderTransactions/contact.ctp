<?php
echo $this->Html->script('contact',array('inline' => false));
echo $this->Html->script(
'http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/js/bootstrapValidator.min.js',
array('inline' => false)
);
echo $this->Html->css('contact',array('inline' => false));

 ?>
	<div class="container">
		<div class="row ">
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
				<div class="row">
					<div class="col-md-12">
						<button type="submit" class="btn main-btn pull-right">Send a message</button>
					</div>
				</div>
			</form>
		</div>
	</div>
