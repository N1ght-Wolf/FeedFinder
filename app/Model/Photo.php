<?php
/**
 * Created by PhpStorm.
 * User: DavidOyeku
 * Date: 26/04/2016
 * Time: 17:17
 */
class Photo extends Model
{
    public $belongsTo = array(
        'User',
        'Venue','Review');



    public function route($query)
    {
        return $this->calculateInterquartile($query);
    }

    public function calculateInterquartile($query)
    {
        $Model = $AnotherModel = ClassRegistry::init($query['explore']['pg_table']);
        $quartile = array("1"=>0,"2"=>0,3=>0,4=>0,5=>0);
        $category = strtolower($query['category']['name']);
        $attr_name = $query['time']['attr_name'];
        //e.g. venue_all
        $column = $category . $attr_name;
        $results = $Model->query("select $column, ntile(5) over (order by $column) as quartile from counties WHERE $column > 0 group by $column order by $column desc");
//        echo "<pre>";
//        print_r($results["0"]["0"][$column]);
//        echo "</pre>";

        foreach ($results as $result => $value) {
            $q = $value['0']['quartile'];
            //  if (!array_key_exists($q, $quartile)) {
            $quartile[$q] = $value['0'][$column];
            // }
        }
        $style = "feedfinder_map_style";

        return array("quartiles" => $quartile, "style" => $style, "layer" => $Model->table);
    }


}
?>