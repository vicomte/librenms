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
	->addDataset('cpu', 'GAUGE');

$resp = snmp_walk($device, $oid, $options, $mib);
list($display_cpu, $display_memory) = explode("\n",$resp);

$fields = array(
	'display-app-mem' => (int)$display_memory,
	'display-app-cpu' => (float)$display_cpu,
);

$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
update_application($app, $resp);
data_update($device, $name, $tags, $fields);
