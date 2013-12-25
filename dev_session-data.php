<?php

include_once("modules/config.php");

echo "Session";
echo "<br>";
print_r($_SESSION);

echo "<br><br>";

echo "Cookies";
echo "<br>";
print_r($_COOKIE);

include_once("modules/func.ga.views.php");

$monthly_metrics = getMonthlyDashboardMetrics();

echo 'Details:';
echo '<br>';
echo '<pre>';
print_r($monthly_metrics);
echo '</pre>';
echo '<br><br>';

$monthly_chart_data = array('visitors' => array(), 'new_visits'=> array());

foreach ($monthly_metrics['rows'] as $obj):
    $date = date_format(date_create_from_format('Ymd', $obj[0]), 'm-d-y');

    array_push($monthly_chart_data['visitors'], array($date, $obj[2]));
    array_push($monthly_chart_data['new_visits'], array($date, $obj[3]));
endforeach;

echo json_encode(($monthly_chart_data), JSON_NUMERIC_CHECK);

?>