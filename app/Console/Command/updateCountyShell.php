<?php 
class updateCountyShell extends AppShell {
	public $uses = array('County');

	public function updateCounty(){
		$result = $this->County->update();
	}

	public function updateSoa(){

	}

	public function updateVenueCounty(){
		$this->County->updateCountyId();
	}
}

?>