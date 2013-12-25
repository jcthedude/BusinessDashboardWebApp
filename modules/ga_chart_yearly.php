<?php

include_once("config.php");
include_once("func.ga.views.php");

$yearly_metrics = getYearlyDashboardMetrics();

$chart_data_yearly = array('visitors' => array(), 'new_visits'=> array());

foreach ($yearly_metrics['rows'] as $obj):
    $date = '01-' . $obj[1] . '-' . $obj[0];
    $timestamp = strtotime($date) * 1000;

    array_push($chart_data_yearly['visitors'], array($timestamp, $obj[2]));
    array_push($chart_data_yearly['new_visits'], array($timestamp, $obj[3]));
endforeach;

echo json_encode(($chart_data_yearly), JSON_NUMERIC_CHECK);

?>