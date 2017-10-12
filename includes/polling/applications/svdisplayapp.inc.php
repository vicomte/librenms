<?php
use LibreNMS\RRD\RrdDefinition;

$name = 'svdisplayapp';
$app_id = $app['app_id'];
$options = '-O qv';
$mib = 'NET-SNMP-EXTEND-MIB';
$oid = '.1.3.6.1.4.1.8072.1.3.2.3';
#$oid_cpu = '.1.3.6.1.4.1.8072.1.3.2.3.1.1.15.100.105.115.112.108.97.121.95.97.112.112.95.99.112.117';
#$oid_bytes = '.1.3.6.1.4.1.8072.1.3.2.3.1.1.15.100.105.115.112.108.97.121.95.97.112.112.95.109.101.109';

$rrd_name = array('app', $name, $app_id);
$rrd_def = RrdDefinition::make()
	->addDataset('mem', 'GAUGE')
	->addDataset('cpu', 'GAUGE')
	->addDataset('6hour_crashes', 'GAUGE')
	->addDataset('missing_heartbeat', 'GAUGE')
        ->addDataset('heartbeat_lags', 'GAUGE')
	->addDataset('dead_port', 'GAUGE');

$resp = snmp_walk($device, $oid, $options, $mib);
list($display_cpu, $display_memory) = explode("\n",$resp);

function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

$ip = gethostbyname($device["hostname"]);
error_log('!!device is ' . $ip);

$mini_status = get_data('https://app.scorevision.com/api/system_monitoring?ip_addr=' . $ip);
error_log("JSON: " . $mini_status);
if (strlen($mini_status) == 0) {
	error_log("setting default return, string was empty");
	$mini_status = "{\"crashes\":0,\"double_disconnected_apps\":0,\"missing_heartbeat\":true,\"display_heartbeat_lags\":false,\"display_app_dead_port\":false,\"slow_response\":0}";	
} 
$json_hash = json_decode($mini_status, true);

$fields = array(
	'display-app-mem' => (int)$display_memory,
	'display-app-cpu' => (float)$display_cpu,
	'display-app-6hour_crashes' => (int)$json_hash["crashes"],
	'display-app-double_disconnected_apps' => (int)$json_hash["double_disconnected_apps"],
	'display-app-missing_heartbeat' => $json_hash["missing_heartbeat"],
	'display-app-heartbeat_lags' => (int)$json_hash["missing_heartbeat"],
	'display-app-dead_port' => (int)$json_hash["display_app_dead_port"]
);

error_log("ARY: " . print_r(array_values($json_hash)));
$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
if ($display_memory > 2100000000 || $display_cpu > .5) { # || (int)$json_hash["crashes"] > 0 || $json_hash["missing_heartbeat"]) {
  $status = "ERROR"; 
} else {
  $status = "OK";
}
update_application($app, $resp, $status);
data_update($device, $name, $tags, $fields);
