<?php
use LibreNMS\RRD\RrdDefinition;

$name = 'svdmemory';
$app_id = $app['app_id'];
$options = '-O qv';
$mib = 'NET-SNMP-EXTEND-MIB';
#$oid = '.1.3.6.1.4.1.8072.1.3.2.3';
#$oid_cpu = '.1.3.6.1.4.1.8072.1.3.2.3.1.1.15.100.105.115.112.108.97.121.95.97.112.112.95.99.112.117';
$oid_bytes = '.1.3.6.1.4.1.8072.1.3.2.3.1.1.15.100.105.115.112.108.97.121.95.97.112.112.95.109.101.109';

$rrd_name = array('app', $name, $app_id);
$rrd_def = RrdDefinition::make()
	->addDataset('mem', 'GAUGE');

#$resp = snmp_walk($device, $oid, $options, $mib);
$display_memory = snmp_get($device, $oid_bytes, $options, $mib);
#snmp_get($device, "sysName.0", "-Oqv", "SNMPv2-MIB")
#list($display_cpu, $display_memory) = explode("\n",$resp);

$fields = array(
	'svdmemory-mem' => (int)$display_memory,
);

$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
error_log("SVDMemory: " . $display_memory);
if ($display_memory > 3750000000) {
  $status = "ERROR"; 
} else {
  $status = "OK";
}
update_application($app, $resp, $status);
data_update($device, $name, $tags, $fields);
