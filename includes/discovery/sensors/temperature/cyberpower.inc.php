<?php

// temperature
$oids = snmp_get($device, '.1.3.6.1.4.1.3808.1.1.4.2.1.0', '-OsqnU');
d_echo($oids."\n");

if ($oids) {
    echo ' Cyberpower Rack Temp';
    list($oid, $temp) = explode(' ', $oids);
    $divisor            = 10;
    $type               = 'cyberpower';
    $descr              = 'Degrees';
    $temp=$temp/10;
    discover_sensor($valid['sensor'], 'temp', $device, $oid, '0', $type, $descr, $divisor, '1', null, null, null, null, $temp);
}
