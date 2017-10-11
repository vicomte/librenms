<?php
use LibreNMS\RRD\RrdDefinition;

$name = 'svdcrashes';
$app_id = $app['app_id'];

$rrd_name = array('app', $name, $app_id);
$rrd_def = RrdDefinition::make()
	->addDataset('count', 'GAUGE');

$ip = gethostbyname($device["hostname"]);

$mini_status = get_data('https://app.scorevision.com/api/system_monitoring?ip_addr=' . $ip);
if (strlen($mini_status) == 0) {
	error_log("setting default return, string was empty");
	$mini_status = "{\"crashes\":0,\"double_disconnected_apps\":0,\"missing_heartbeat\":true,\"display_heartbeat_lags\":false,\"display_app_dead_port\":false,\"slow_response\":0}";	
} 
$json_hash = json_decode($mini_status, true);
$test_val = (int)$json_hash["crashes"];
error_log("SVDCrashes6hr:" . $test_val);
$fields = array(
#	'display-app-mem' => (int)$display_memory,
#	'display-app-cpu' => (float)$display_cpu,
	'svdcrashes-count' => $test_val,
#	'display-app-double_disconnected_apps' => (int)$json_hash["double_disconnected_apps"],
#	'display-app-missing_heartbeat' => $json_hash["missing_heartbeat"],
#	'display-app-heartbeat_lags' => (int)$json_hash["missing_heartbeat"],
#	'display-app-dead_port' => (int)$json_hash["display_app_dead_port"]
);

$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
if ($test_val > 0) {
  $status = "ERROR"; 
} else {
  $status = "OK";
}
update_application($app, $resp, $status);
data_update($device, $name, $tags, $fields);
