<?php
/**
 * Time.php
 *
 * -Description-
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
 * @copyright  2018 Tony Murray
 * @author     Tony Murray <murraytony@gmail.com>
 */

namespace LibreNMS\Util;

class Time
{
    public static function legacyTimeSpecToSecs($description)
    {
        $conversion = [
            'now' => 0,
            'onehour' => 3600,
            'fourhour' => 14400,
            'sixhour' => 21600,
            'twelvehour' => 43200,
            'day' => 86400,
            'twoday' => 172800,
            'week' => 604800,
            'twoweek' => 1209600,
            'month' => 2678400,
            'twomonth' => 5356800,
            'threemonth' => 8035200,
            'year' => 31536000,
            'twoyear' => 63072000,
        ];

        return isset($conversion[$description]) ? $conversion[$description] : 0;
    }
}
