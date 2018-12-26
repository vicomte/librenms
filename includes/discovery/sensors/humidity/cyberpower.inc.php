<?php

// humidity
$oids = snmp_get($device, '.1.3.6.1.4.1.3808.1.1.4.3.1.0', '-OsqnU');
d_echo($oids."\n");

if ($oids) {
    echo ' Cyberpower Rack Humidity';
    list($oid, $humidity) = explode(' ', $oids);
    $divisor            = 1;
    $type               = 'cyberpower';
    $descr              = 'Percent';
    discover_sensor($valid['sensor'], 'humidity', $device, $oid, '0', $type, $descr, $divisor, '1', null, null, null, null, $humidity);
}
