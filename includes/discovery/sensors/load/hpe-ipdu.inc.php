<?php
/**
 * hpe-ipdu.inc.php
 *
 * LibreNMS sensors load discovery module for HPE iPDU
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    LibreNMS
 * @link       http://librenms.org
 * @copyright  2017 Neil Lathwood
 * @author     Neil Lathwood <neil@lathwood.co.uk>
 */


echo 'HPE iPDU Load ';

$x=1;
foreach ($pre_cache['hpe_ipdu'] as $index => $item) {
    if (isset($item['mpduOutputPercentLoad'])) {
        $oid = '.1.3.6.1.4.1.232.165.5.2.1.1.5.' . $index;
        $current = $item['mpduOutputPercentLoad'];
        discover_sensor($valid['sensor'], 'load', $device, $oid, 'mpduOutputPercentLoad.'.$index, 'hpe-ipdu', "MPDU #$x Load", 10, 1, null, null, null, null, $current);
        $x++;
    }
}//end foreach

unset(
    $item,
    $oid,
    $index,
    $item,
    $x
);
