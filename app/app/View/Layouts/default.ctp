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
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.js"></script>
		<script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js"></script>
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
		<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/js/bootstrapValidator.min.js'></script>


		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link href="https://rawgit.com/Leaflet/Leaflet.markercluster/master/dist/MarkerCluster.css" rel="stylesheet">
		<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.css" />
		<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"/>
		<link href="https://rawgit.com/Leaflet/Leaflet.markercluster/master/dist/MarkerCluster.Default.css" rel="stylesheet"/>
		<link href="https://rawgit.com/davicustodio/Leaflet.StyledLayerControl/master/css/styledLayerControl.css" rel="stylesheet"/>
		<link href="https://rawgit.com/dreyescat/bootstrap-rating/master/bootstrap-rating.css" rel="stylesheet"/>



		<?php


          echo $this->fetch('meta');
          echo $this->fetch('css');
          echo $this->fetch('script');

                echo $this->Html->meta('icon');
                    echo $this->Html->script('jquery-ui');

                    echo $this->Html->script('leaflet-map');
                    echo $this->Html->script('contact');
                    // echo $this->Html->script('leaflet-sidebar');
                    // echo $this->Html->script('Control.Geocoder');
                    // echo $this->Html->script('highstock');
                    echo $this->Html->script('url.min');
                    // echo $this->Html->script('Control.Loading');

                    echo $this->Html->css('main');
                    echo $this->Html->css('jquery-ui');
                    echo $this->Html->css('bootstrap.min');

                    echo $this->Html->css('Control.Loading');
                    // echo $this->Html->script('stats');
										echo $this->Html->css('contact', array('inline' => false));
?>


	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>


</head>
<body>
<?php echo $this->element('navbar');?>

	<div id='main-container'class="content container-fluid">
		<?php
        echo $this->fetch('content');
			?>
		<div id="footer">

		</div>
	</div>
	<?php
    // echo $this->element('sql_dump');
     echo $this->Js->writeBuffer();

    ?>

</body>
</html>
