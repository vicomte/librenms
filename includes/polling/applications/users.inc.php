<?php
use LibreNMS\RRD\RrdDefinition;

$name = 'users';
$app_id = $app['app_id'];
$options = '-Oqv';
$mib = 'HOST-RESOURCES-MIB';
$oid = '.1.3.6.1.2.1.25.1.5.0';

$rrd_name = array('app', $name, $app_id);
$rrd_def = RrdDefinition::make()->addDataset('logins', 'GAUGE', 0);

$login_count = snmp_get($device, $oid, $options, $mib);

$fields = array('logins' => $login_count,);

$tags = array('name' => $name, 'app_id' => $app_id, 'rrd_def' => $rrd_def, 'rrd_name' => $rrd_name);
data_update($device, 'app', $tags, $fields);
update_application($app, $login_count, $fields, $login_count);
