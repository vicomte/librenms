<?php

global $config;

$graphs = array(
    'svdisplayapp_mem'       => 'Memory',
    'svdisplayapp_cpu'       => 'CPU',
    'svdisplayapp_visible'	=> 'Visible',
    'svdisplayapp_missing-heartbeat' => 'Missed Heartbeats',
    'svdisplayapp_heartbeat-lags' => 'Heartbeat Lags System',
    'svdisplayapp_6hour-crashes' => 'Crashes last 6 hours',
    'svdisplayapp_double-disconnected-apps' => 'Games where both Connection methods became disconnected',
    'svdisplayapp_dead-port' => 'Port 4552 Offline',
);

foreach ($graphs as $key => $text) {
    $graph_type = $key;

    $graph_array['height'] = '100';
    $graph_array['width']  = '215';
    $graph_array['to']     = $config['time']['now'];
    $graph_array['id']     = $app['app_id'];
    $graph_array['type']   = 'application_'.$key;

    echo '<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">'.$text.'</h3>
    </div>
    <div class="panel-body">
    <div class="row">';
    include 'includes/print-graphrow.inc.php';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
