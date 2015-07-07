<?php

App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');

/**
 * Static content controller.
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class SiteUsageLogsController extends AppController
{
    public $components = array('Session','Highcharts.Highcharts');
    public $helpers = array('Html', 'Form','Session','Js' => array('Jquery'));
    public $uses = array();
    public $layout = 'Highcharts.chart.demo';
    public $chartData = array();
    public $xAxis = array();
    public $Highcharts = null;

    public function index()
    {
        //get the first 20 logs :P
    $this->set('logs', $this->SiteUsageLog->find('all', array('limit' => 20)));
    }

    public function mostActive()
    {
        // $this->set('log',$this->SiteUsageLog->query("SELECT *, count(user_id) FROM site_usage_logs"));
    $this->set('log', $this->SiteUsageLog->find('all', array('fields' => array('COUNT(user_id) as transaction_count', '*'))));
    }

    public function mostActiveGraph()
    {
        $chartName = 'Line Chart';
        $hours = 0;
        $firstDate = new DateTime();
        $secondDate = new DateTime();

        $results = $this->SiteUsageLog->find(
        'all',array('conditions' => array('user_id' => 1),
        'fields' => array('user_id', 'created'),
        'limit' => 5000));

        foreach ($results as $result) {
            // convert to month
        $month = CakeTime::format($result['SiteUsageLog']['created'], '%B %Y');
        //check if month already in xAxis
        if (!in_array($month, $this->xAxis)) {
            $firstDate->modify($result['SiteUsageLog']['created']);
            $this->xAxis[] = $month;
            $this->chartData[] = $hours;
            $hours = 0; //reset
        } else {
            $secondDate->modify($result['SiteUsageLog']['created']);
            $diff = $secondDate->diff($firstDate);
            $hours = $diff->h;
            $hours = $hours + ($diff->days * 24);
        }
        }

    //make a new chart
    $mychart = $this->Highcharts->create($chartName, 'line');
    //set params for chart
    $this->Highcharts->setChartParams($chartName, array(
        'renderTo' => 'linewrapper', // div to display chart inside
        'chartWidth' => 800,
        'chartHeight' => 600,
        'chartMarginTop' => 60,
        'chartMarginLeft' => 90,
        'chartMarginRight' => 30,
        'chartMarginBottom' => 110,
        'chartSpacingRight' => 10,
        'chartSpacingBottom' => 15,
        'chartSpacingLeft' => 0,
        'chartAlignTicks' => false,
        'chartBackgroundColorLinearGradient' => array(0, 0, 0, 300),
        'chartBackgroundColorStops' => array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)')),
        'title' => 'Hours Spent',
        'titleAlign' => 'left',
        'titleFloating' => true,
        'titleStyleFont' => '18px Metrophobic, Arial, sans-serif',
        'titleStyleColor' => '#0099ff',
        'titleX' => 20,
        'titleY' => 20,
        'legendEnabled' => true,
        'legendLayout' => 'horizontal',
        'legendAlign' => 'center',
        'legendVerticalAlign ' => 'bottom',
        'legendItemStyle' => array('color' => '#222'),
        'legendBackgroundColorLinearGradient' => array(0, 0, 0, 25),
        'legendBackgroundColorStops' => array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)')),
        'tooltipEnabled' => false,
        'xAxisLabelsEnabled' => true,
        'xAxisLabelsAlign' => 'right',
        'xAxisLabelsStep' => 1,
        'xAxislabelsX' => 5,
        'xAxisLabelsY' => 20,
        'xAxisCategories' => $this->xAxis,
        'yAxisTitleText' => 'Hours',
        'enableAutoStep' => false,
            )
    );

        $series = $this->Highcharts->addChartSeries();
        $series->addName('Hours')->addData($this->chartData);
        $mychart->addSeries($series);
        $this->set(compact('chartName'));
    }
}
