<?php
class UpdateShell extends AppShell
{
    public $uses = array('County', 'Soa');

    public function updatePgTables()
    {
        $this->County->updateCountyId();
        $this->out('updated county_id in venues table...');
        $this->Soa->updateSoaId();
        $this->out('updated soa_id in venues table...');


        //update values in pg tables
        $this->County->updateColumns();
        $this->out('updated the county column values...');
        $this->Soa->updateColumns();
        $this->out('updated the soa column values...');

    }
}

?>
//cd /Applications/MAMP/htdocs/FeedFinder/app && Console/cake updateCounty updateVenueCounty