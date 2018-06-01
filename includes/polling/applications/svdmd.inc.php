<?php
use LibreNMS\RRD\RrdDefinition;

$name = 'svdmd';
$app_id = $app['app_id'];

$options = '-O qv';
$mib = 'NET-SNMP-EXTEND-MIB';

$oid_bytes = '.1.3.6.1.4.1.8072.1.3.2.3.1.1.17.100.105.115.112.108.97.121.95.109.100.95.97.99.116.105.118.101';

$rrd_name = array('app', $name, $app_id);
$rrd_def = RrdDefinition::make()
	->addDataset('md', 'GAUGE');

$port_active = snmp_get($device, $oid_bytes, $options, $mib);
#error_log("MD: " . $port_active);

$fields = array(
	'svdmd-md' => (int)$port_active
);

error_log("SVDMD: " . $port_active);
$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
if ($port_active == 0) { 
  $status = "ERROR"; 
} else {
  $status = "OK";
}
update_application($app, $resp, $status);
data_update($device, $name, $tags, $fields);
