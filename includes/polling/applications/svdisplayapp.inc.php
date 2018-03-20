<?php
use LibreNMS\RRD\RrdDefinition;

$name = 'svdisplayapp';
$app_id = $app['app_id'];
$options = '-O qv';
$mib = 'NET-SNMP-EXTEND-MIB';
$oid_bytes_mem = '.1.3.6.1.4.1.8072.1.3.2.3.1.1.15.100.105.115.112.108.97.121.95.97.112.112.95.109.101.109';
$oid_bytes_proc = '.1.3.6.1.4.1.8072.1.3.2.3.1.1.15.100.105.115.112.108.97.121.95.97.112.112.95.99.112.117';

$rrd_name = array('app', $name, $app_id);
$rrd_def = RrdDefinition::make()
	->addDataset('mem', 'GAUGE')
	->addDataset('cpu', 'GAUGE')
	->addDataset('6hour_crashes', 'GAUGE')
	->addDataset('double_disconnected', 'GAUGE')
	->addDataset('missing_heartbeat', 'GAUGE')
        ->addDataset('heartbeat_lags', 'GAUGE')
	->addDataset('dead_port', 'GAUGE')
	->addDataset('visible', 'GAUGE');

$ip = gethostbyname($device["hostname"]);

$mini_status = get_data('https://app.scorevision.com/api/system_monitoring?ip_addr=' . $ip);
#error_log("JSON: " . $mini_status);
if (strlen($mini_status) == 0) {
	error_log("setting default return, string was empty");
	$mini_status = "{\"crashes\":0,\"double_disconnected_apps\":0,\"missing_heartbeat\":true,\"display_heartbeat_lags\":false,\"display_app_dead_port\":false,\"slow_response\":0,\"display_visible\":0}";	
} 
$json_hash = json_decode($mini_status, true);

$mem = snmp_get($device, $oid_bytes_mem, $options, $mib);
$proc = snmp_get($device, $oid_bytes_proc, $options, $mib);

$fields = array(
	'svdisplayapp-mem' => (int)$mem,
	'svdisplayapp-cpu' => (float)$proc,
	'svdisplayapp-6hour_crashes' => (int)$json_hash["crashes"],
	'svdisplayapp-double_disconnected' => (int)$json_hash["double_disconnected_apps"],
	'svdisplayapp-missing_heartbeat' => (int)$json_hash["missing_heartbeat"],
	'svdisplayapp-heartbeat_lags' => (int)$json_hash["display_heartbeat_lags"],
	'svdisplayapp-dead_port' => (int)$json_hash["display_app_dead_port"],
	'svdisplayapp-visible' => $json_hash["display_visible"] ? 1 : 0
);

#error_log("ARY: " . print_r(array_values($fields)));
$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
if ($display_memory > 2100000000 || $display_cpu > .5) { # || (int)$json_hash["crashes"] > 0 || $json_hash["missing_heartbeat"]) {
  $status = "ERROR"; 
} else {
  $status = "OK";
}
update_application($app, $resp, $status);
data_update($device, $name, $tags, $fields);
