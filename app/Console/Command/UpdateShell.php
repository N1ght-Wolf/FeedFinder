<?php
class UpdateShell extends AppShell {
    public $uses = array('County','Soa');

    public function updatePgTables(){
        $this->County->updateCountyId();
        $this->out('updated county_id in venues table.');
        $this->Soa->updateSoaId();
        $this->out('updated soa_id in venues table.');
        //update values in pg tables
//        $this->County->update();
//        $this->Soa->update();

    }

    public function updateVenuePgId(){
        $this->County->updateCountyId();
        $this->Soa->updateSoaId();
    }
}

?>
//cd /Applications/MAMP/htdocs/FeedFinder/app && Console/cake updateCounty updateVenueCounty