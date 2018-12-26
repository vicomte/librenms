<?php
use LibreNMS\RRD\RrdDefinition;

$name = 'svdheartbeat';
$app_id = $app['app_id'];

$rrd_name = array('app', $name, $app_id);
$rrd_def = RrdDefinition::make()
	->addDataset('missedbeat', 'GAUGE');

$ip = gethostbyname($device["hostname"]);

$mini_status = get_data('https://app.scorevision.com/api/system_monitoring?ip_addr=' . $ip);
if (strlen($mini_status) == 0) {
	error_log("setting default return, string was empty");
	$mini_status = "{\"crashes\":0,\"double_disconnected_apps\":0,\"missing_heartbeat\":true,\"display_heartbeat_lags\":false,\"display_app_dead_port\":false,\"slow_response\":0}";	
} 
$json_hash = json_decode($mini_status, true);

$test_val = (int)$json_hash["missing_heartbeat"];
$fields = array(
	'svdheartbeat-missedbeat' => $test_val,
);
$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
error_log("SVDheartbeat:" . $test_val);
if ($test_val > 0) {
  $status = "ERROR"; 
} else {
  $status = "OK";
}
update_application($app, $resp, $status);
data_update($device, $name, $tags, $fields);
