<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org).
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.10.0.1076
 *
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset();
    ?>
		<!-- <link href='https://api.mapbox.com/mapbox.js/v2.2.1/mapbox.css' rel='stylesheet' /> -->
		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css" />
		<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

		<?php
		$this->Html->script('http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js', false);

          echo $this->fetch('meta');
          echo $this->fetch('css');
          echo $this->fetch('script');

  				echo $this->Html->meta('icon');
					echo $this->Html->script('jquery');
					echo $this->Html->script('jquery-ui');
					echo $this->Html->script('leaflet-map');
					echo $this->Html->script('leaflet-sidebar');
					echo $this->Html->script('Control.Geocoder');
					echo $this->Html->script('highstock.js');


  				//echo $this->Html->css('cake.generic');
					echo $this->Html->css('main');
					echo $this->Html->css('jquery-ui');
					echo $this->Html->css('leaflet-sidebar');
					echo $this->Html->css('bootstrap.min');
					echo $this->Html->css('Control.Geocoder');
					echo $this->Html->script('stats');



					//echo $this->Html->script('highmaps');
?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>


</head>
<body>
	<div id="container">
		<div id="header">
		</div>
		<div id="content">
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">

		</div>
	</div>
	<?php
     echo $this->element('sql_dump');
     echo $this->Js->writeBuffer();

    ?>

</body>
</html>
