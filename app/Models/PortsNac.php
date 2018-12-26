<?php
/**
 * PortsNac.php
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

namespace App\Models;

class PortsNac extends BaseModel
{
    protected $table = 'ports_nac';
    protected $primaryKey = 'ports_nac_id';
    public $timestamps = false;
    protected $fillable = [
        'auth_id',
        'device_id',
        'port_id',
        'domain',
        'username',
        'mac_address',
        'ip_address',
        'host_mode',
        'authz_status',
        'authz_by',
        'authc_status',
        'method',
        'timeout',
        'time_left',
    ];


    public function scopeHasAccess($query, User $user)
    {
        return $this->hasPortAccess($query, $user);
    }

    // ---- Define Relationships ----

    public function device()
    {
        return $this->belongsTo('App\Models\Device', 'device_id', 'device_id');
    }

    public function port()
    {
        return $this->belongsTo('App\Models\Port', 'port_id', 'port_id');
    }
}
