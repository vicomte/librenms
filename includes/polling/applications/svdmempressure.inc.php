<?php
use LibreNMS\RRD\RrdDefinition;

$name = 'svdmempressure';
$app_id = $app['app_id'];
$options = '-O qv';
$mib = 'NET-SNMP-EXTEND-MIB';
$oid_bytes = '.1.3.6.1.4.1.8072.1.3.2.3.1.1.20.100.105.115.112.108.97.121.95.109.101.109.95.112.114.101.115.115.117.114.101';

$rrd_name = array('app', $name, $app_id);
$rrd_def = RrdDefinition::make()
	->addDataset('pressure', 'GAUGE');

$display_memory = snmp_get($device, $oid_bytes, $options, $mib);

$fields = array(
	'svdmempressure-pressure' => (int)$display_memory,
);

$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
error_log("SVDMemPressure: " . $display_memory);
if ($display_memory > 2) {
  $status = "ERROR"; 
} else {
  $status = "OK";
}
update_application($app, $resp, $fields, $status);

data_update($device, $name, $tags, $fields);
