<?php

$scale_min = 0;

require 'includes/graphs/common.inc.php';

$apache_rrd = rrd_name($device['hostname'], array('app', 'svdmempressure', $app['app_id']));

if (rrdtool_check_rrd_exists($apache_rrd)) {
    $rrd_filename = $apache_rrd;
}

$ds = 'pressure';

$colour_area = 'CDEB8B';
$colour_line = '006600';

$colour_area_max = 'FFEE99';

$graph_max  = 4;
$multiplier = 1;

$unit_text = 'bytes';

require 'includes/graphs/generic_simplex.inc.php';
