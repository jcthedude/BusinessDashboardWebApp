<?php

include_once("config.php");
include_once("func.ga.views.php");

$monthly_metrics = getMonthlyDashboardMetrics();

$chart_data_monthly = array('visitors' => array(), 'new_visits'=> array());

foreach ($monthly_metrics['rows'] as $obj):
    $date = date_format(date_create_from_format('Ymd', $obj[0]), 'd-m-Y');
    $timestamp = strtotime($date) * 1000;

    array_push($chart_data_monthly['visitors'], array($timestamp, $obj[2]));
    array_push($chart_data_monthly['new_visits'], array($timestamp, $obj[3]));
endforeach;

echo json_encode(($chart_data_monthly), JSON_NUMERIC_CHECK);

?>