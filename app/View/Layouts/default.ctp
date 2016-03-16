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
	<?php 
		echo $this->Html->charset();
    ?>

	<?php
		  //load general scripts 
	 echo $this->Html->script('jquery.min');
	 echo $this->Html->script('https://code.getmdl.io/1.1.2/material.min.js', array('inline'=>false));
    
		  // load general CSS styles
     echo $this->Html->css('https://fonts.googleapis.com/icon?family=Material+Icons', array('inline'=>false));
     echo $this->Html->css('https://code.getmdl.io/1.1.2/material.indigo-pink.min.css', array('inline'=>false));
     echo $this->Html->css('https://rawgit.com/FortAwesome/Font-Awesome/master/css/font-awesome.min.css', array('inline'=>false));

     echo $this->fetch('meta');
     echo $this->fetch('css');
     echo $this->fetch('script');
     echo $this->Html->meta('icon');



?>	


	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('Feed Finder'); ?>
	</title>


</head>
<body>

	<div id='container'>
	<?php echo $this->element('navbar'); ?>
	<?php echo $this->fetch('content');?>
		<!-- Footer begins -->
		<div id="footer">

		</div>
		<!-- Footer ends -->
	</div>

	<?php
    // echo $this->element('sql_dump');
     echo $this->Js->writeBuffer();

    ?>

</body>
</html>
