<?php 
class updateCountyShell extends AppShell {
	public $uses = array('County');

	public function updateUser(){
		$result = $this->County->update();
		print_r($result);
	}
}

?>