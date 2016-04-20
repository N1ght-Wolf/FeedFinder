<?php 
class UpdateShell extends AppShell {
	public $uses = array('County','Soa');

	public function updateCounty(){
		$result = $this->County->update();
	}

	public function updateSuperOutArea(){
		$this->Soa->update();
	}

	public function updateVenueCounty(){
		$this->County->updateCountyId();
	}
}

?>
//cd /Applications/MAMP/htdocs/FeedFinder/app && Console/cake updateCounty updateVenueCounty