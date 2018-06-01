<?php
use LibreNMS\RRD\RrdDefinition;

$name = 'svdproc';
$app_id = $app['app_id'];
$options = '-O qv';
$mib = 'NET-SNMP-EXTEND-MIB';
#$oid = '.1.3.6.1.4.1.8072.1.3.2.3';
$oid = '.1.3.6.1.4.1.8072.1.3.2.3.1.1.15.100.105.115.112.108.97.121.95.97.112.112.95.99.112.117';
#$oid_bytes = '.1.3.6.1.4.1.8072.1.3.2.3.1.1.15.100.105.115.112.108.97.121.95.97.112.112.95.109.101.109';

$rrd_name = array('app', $name, $app_id);
$rrd_def = RrdDefinition::make()
	->addDataset('cpu', 'GAUGE');

$display_cpu = snmp_get($device, $oid, $options, $mib);

$fields = array(
	'svdproc-cpu' => (int)$display_cpu,
);

$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
error_log("SVDProc: " . $display_cpu);
if ($display_cpu > 0.5) {
  $status = "ERROR"; 
} else {
  $status = "OK";
}
update_application($app, $resp, $status);
data_update($device, $name, $tags, $fields);
