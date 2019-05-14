<nav class="navbar navbar-default {{ $navbar }} navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navHeaderCollapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="hidden-md hidden-sm navbar-brand" href>
            @if($title_image)
                <img src="{{ asset($title_image) }}" alt="{{ $project_name }}">
            @else
                {{ $project_name }}
            @endif
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navHeaderCollapse">
            <ul class="nav navbar-nav">
{{-- Overview --}}
                <li class="dropdown">
                    <a href="{{ url('overview') }}" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-home fa-fw fa-lg fa-nav-icons hidden-md" aria-hidden="true"></i> <span class="hidden-sm">Overview</span></a>
                    <ul class="dropdown-menu multi-level" role="menu">
                        <li><a href="{{ url('overview') }}"><i class="fa fa-tv fa-fw fa-lg" aria-hidden="true"></i> Dashboard</a></li>
                        <li class="dropdown-submenu">
                            <a href="{{ url('overview') }}"><i class="fa fa-map fa-fw fa-lg" aria-hidden="true"></i> Maps</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('availability-map') }}"><i class="fa fa-arrow-circle-up fa-fw fa-lg" aria-hidden="true"></i> Availability</a></li>
                                <li><a href="{{ url('map') }}"><i class="fa fa-sitemap fa-fw fa-lg" aria-hidden="true"></i> Network</a></li>
                                @if($device_groups->isNotEmpty())
                                    <li class="dropdown-submenu"><a href="#"><i class="fa fa-th fa-fw fa-lg" aria-hidden="true"></i> Device Groups Maps</a><ul class="dropdown-menu scrollable-menu">
                                        @foreach($device_groups as $group)
                                            <li><a href="{{ url('map', [$group->id]) }}" title="{{ $group->desc }}"><i class="fa fa-th fa-fw fa-lg" aria-hidden="true"></i>
                                                {{ ucfirst($group->name) }}
                                            </a></li>
                                        @endforeach
                                    </ul></li>
                                @endif
                                <li><a href="{{ url('fullscreenmap') }}"><i class="fa fa-expand fa-fw fa-lg" aria-hidden="true"></i> Geographical</a></li>
                            </ul>
                        </li>
                        @if(auth()->user()->isAdmin() || \LibreNMS\Plugins::count())
                        <li class="dropdown-submenu">
                            <a><i class="fa fa-plug fa-fw fa-lg" aria-hidden="true"></i> Plugins</a>
                            <ul class="dropdown-menu">
                                {!! \LibreNMS\Plugins::call('menu') !!}
                                @admin
                                    @if(\LibreNMS\Plugins::count())
                                        <li role="presentation" class="divider"></li>
                                    @endif
                                <li><a href="{{ url('plugin/view=admin') }}"> <i class="fa fa-lock fa-fw fa-lg"
                                                                                 aria-hidden="true"></i>Plugin Admin</a>
                                </li>
                                @endadmin
                            </ul>
                        </li>
                        @endif
                        <li class="dropdown-submenu">
                            <a href="{{ url('overview') }}"><i class="fa fa-wrench fa-fw fa-lg" aria-hidden="true"></i> Tools</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('ripenccapi') }}"><i class="fa fa-star fa-fw fa-lg" aria-hidden="true"></i> RIPE NCC API</a></li>
                                @config('oxidized.enabled')
                                    <li><a href="{{ url('oxidized') }}"><i class="fa fa-stack-overflow fa-fw fa-lg" aria-hidden="true"></i> Oxidized</a></li>
                                @endconfig
                            </ul>
                        </li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="{{ url('eventlog') }}"><i class="fa fa-bookmark fa-fw fa-lg" aria-hidden="true"></i> Eventlog</a></li>
                        @config('enable_syslog')
                            <li><a href="{{ url('syslog') }}"><i class="fa fa-clone fa-fw fa-lg" aria-hidden="true"></i> Syslog</a></li>
                        @endconfig
                        @config('graylog.server')
                            <li><a href="{{ url('graylog') }}"><i class="fa fa-clone fa-fw fa-lg" aria-hidden="true"></i> Graylog</a></li>
                        @endconfig

                        <li><a href="{{ url('inventory') }}"><i class="fa fa-cube fa-fw fa-lg" aria-hidden="true"></i> Inventory</a></li>
                        @if($package_count)
                            <li><a href="{{ url('search/search=packages') }}"><i class="fa fa-archive fa-fw fa-lg" aria-hidden="true"></i> Packages</a></li>
                        @endif

                        <li role="presentation" class="divider"></li>
                        <li><a href="{{ url('search/search=ipv4') }}"><i class="fa fa-search fa-fw fa-lg" aria-hidden="true"></i> IPv4 Address</a></li>
                        <li><a href="{{ url('search/search=ipv6') }}"><i class="fa fa-search fa-fw fa-lg" aria-hidden="true"></i> IPv6 Address</a></li>
                        <li><a href="{{ url('search/search=mac') }}"><i class="fa fa-search fa-fw fa-lg" aria-hidden="true"></i> MAC Address</a></li>
                        <li><a href="{{ url('search/search=arp') }}"><i class="fa fa-search fa-fw fa-lg" aria-hidden="true"></i> ARP Tables</a></li>
                        <li><a href="{{ url('search/search=fdb') }}"><i class="fa fa-search fa-fw fa-lg" aria-hidden="true"></i> FDB Tables</a></li>
                        @config('poller_modules.mib')
                            <li role="presentation" class="divider"></li>
                            <li><a href="{{ url('mibs') }}"><i class="fa fa-file-text-o fa-fw fa-lg" aria-hidden="true"></i> MIB definitions</a></li>
                        @endconfig
                    </ul>
                </li>
{{-- Devices --}}
                <li class="dropdown">
                    <a href="{{ url('devices/') }}" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-server fa-fw fa-lg fa-nav-icons hidden-md" aria-hidden="true"></i> <span class="hidden-sm">Devices</span></a>
                    <ul class="dropdown-menu">
                    @if($device_types->isNotEmpty())
                        <li class="dropdown-submenu">
                            <a href="{{ url('devices') }}"><i class="fa fa-server fa-fw fa-lg" aria-hidden="true"></i> All Devices</a>
                            <ul class="dropdown-menu scrollable-menu">
                            @foreach($device_types as $type)
                                <li><a href="{{ url("devices/type=$type") }}"><i class="fa fa-angle-double-right fa-fw fa-lg" aria-hidden="true"></i> {{ ucfirst($type) }}</a></li>
                            @endforeach
                        </ul></li>
                    @else
                        <li class="dropdown-submenu"><a href="#">No devices</a></li>
                    @endif

                    @if($device_groups->isNotEmpty())
                        <li class="dropdown-submenu"><a href="#"><i class="fa fa-th fa-fw fa-lg" aria-hidden="true"></i> Device Groups</a>
                            <ul class="dropdown-menu scrollable-menu">
                            @foreach($device_groups as $group)
                                <li><a href="{{ url("devices/group=$group->id") }}" title="{{ $group->desc }}"><i class="fa fa-th fa-fw fa-lg" aria-hidden="true"></i> {{ ucfirst($group->name) }}</a></li>
                            @endforeach
                            </ul>
                        </li>
                    @endif

                    @if($locations->isNotEmpty())
                        <li role="presentation" class="divider"></li>
                        <li class="dropdown-submenu">
                            <a href="#"><i class="fa fa-map-marker fa-fw fa-lg" aria-hidden="true"></i> @lang('Geo Locations')</a>
                            <ul class="dropdown-menu scrollable-menu">
                                <li><a href="{{ url('locations') }}"><i class="fa fa-map-marker fa-fw fa-lg" aria-hidden="true"></i> @lang('All Locations')</a></li>
                            @foreach($locations as $location)
                                    <li><a href="{{ url("devices/location=" . $location->id) }}"><i class="fa fa-building fa-fw fa-lg" aria-hidden="true"></i> {{ $location->display() }}</a></li>
                            @endforeach
                            </ul>
                        </li>
                    @endif
                    @admin
                        <li role="presentation" class="divider"></li>
                        @config('poller_modules.mib')
                            <li><a href="{{ url('mib_assoc') }}"><i class="fa fa-file-text-o fa-fw fa-lg" aria-hidden="true"></i> MIB associations</a></li>
                            <li role="presentation" class="divider"></li>
                        @endconfig

                        @notconfig('navbar.manage_groups.hide')
                                <li><a href="{{ url('device-groups') }}"><i class="fa fa-th fa-fw fa-lg" aria-hidden="true"></i> Manage Groups</a></li>
                        @endconfig
                        <li><a href="{{ url('device-dependencies') }}"><i class="fa fa-group fa-fw fa-lg"></i> Device Dependencies</a></li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="{{ url('addhost') }}"><i class="fa fa-plus fa-fw fa-lg" aria-hidden="true"></i> Add Device</a></li>
                        <li><a href="{{ url('delhost') }}"><i class="fa fa-trash fa-fw fa-lg" aria-hidden="true"></i> Delete Device</a></li>
                    @endadmin

                    </ul>
                </li>
{{-- Services --}}
                @config('show_services')
                    <li class="dropdown">
                        <a href="{{ url('services') }}" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-cogs fa-fw fa-lg fa-nav-icons hidden-md" aria-hidden="true"></i> <span class="hidden-sm">Services</span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('services') }}"><i class="fa fa-cogs fa-fw fa-lg" aria-hidden="true"></i> All Services </a></li>
                            @if($service_counts['warning'] || $service_counts['critical'])
                                <li role="presentation" class="divider"></li>
                                @if($service_counts['warning'])
                                    <li><a href="{{ url('services/state=warning') }}"><i class="fa fa-bell fa-col-warning fa-fw fa-lg" aria-hidden="true"></i> Warning ({{ $service_counts['warning'] }})</a></li>
                                @endif
                                @if($service_counts['critical'])
                                    <li><a href="{{ url('services/state=critical') }}"><i class="fa fa-bell fa-col-danger fa-fw fa-lg" aria-hidden="true"></i> Critical ({{ $service_counts['critical'] }})</a></li>
                                @endif
                            @endif
                            @admin
                                <li role="presentation" class="divider"></li>
                                <li><a href="{{ url('addsrv') }}"><i class="fa fa-plus fa-fw fa-lg" aria-hidden="true"></i> Add Service</a></li>
                            @endadmin
                        </ul>
                    </li>
                @endconfig
{{-- Ports --}}
                <li class="dropdown">
                    <a href="{{ url('ports') }}" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-link fa-fw fa-lg fa-nav-icons hidden-md" aria-hidden="true"></i> <span class="hidden-sm">Ports</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('ports') }}"><i class="fa fa-link fa-fw fa-lg" aria-hidden="true"></i> All Ports</a></li>

                        @if($port_counts['errored'] > 0)
                            <li><a href="{{ url('ports/errors=yes') }}"><i class="fa fa-exclamation-circle fa-fw fa-lg" aria-hidden="true"></i> Errored ({{ $port_counts['errored'] }})</a></li>
                        @endif

                        @if($port_counts['ignored'] > 0)
                            <li><a href="{{ url('ports/ignore=yes') }}"><i class="fa fa-question-circle fa-fw fa-lg" aria-hidden="true"></i> Ignored ({{ $port_counts['ignored'] }})</a></li>
                        @endif

                        @config('enable_billing')
                            <li><a href="{{ url('bills') }}"><i class="fa fa-money fa-fw fa-lg" aria-hidden="true"></i> Traffic Bills</a></li>
                        @endconfig

                        @if($port_counts['pseudowire'] > 0)
                            <li><a href="{{ url('pseudowires') }}"><i class="fa fa-arrows-alt fa-fw fa-lg" aria-hidden="true"></i> Pseudowires</a></li>
                        @endif

                        @if(auth()->user()->hasGlobalRead())
                            @if($port_groups_exist)
                                <li role="presentation" class="divider"></li>
                                @config('int_customers')
                                    <li><a href="{{ url('customers') }}"><i class="fa fa-users fa-fw fa-lg" aria-hidden="true"></i> Customers</a></li>
                                @endconfig
                                @config('int_l2tp')
                                    <li><a href="{{ url('iftype/type=l2tp') }}"><i class="fa fa-link fa-fw fa-lg" aria-hidden="true"></i> L2TP</a></li>
                                @endconfig
                                @config('int_transit')
                                    <li><a href="{{ url('iftype/type=transit') }}"><i class="fa fa-truck fa-fw fa-lg" aria-hidden="true"></i> Transit</a></li>
                                @endconfig
                                @config('int_peering')
                                    <li><a href="{{ url('iftype/type=peering') }}"><i class="fa fa-handshake-o fa-fw fa-lg" aria-hidden="true"></i> Peering</a></li>
                                @endconfig
                                @if(\LibreNMS\Config::get('int_peering') && \LibreNMS\Config::get('int_transit'))
                                    <li><a href="{{ url('iftype/type=peering,transit') }}"><i class="fa fa-rocket fa-fw fa-lg" aria-hidden="true"></i> Peering + Transit</a></li>
                                @endif
                                @config('int_core')
                                    <li><a href="{{ url('iftype/type=core') }}"><i class="fa fa-code-fork fa-fw fa-lg" aria-hidden="true"></i> Core</a></li>
                                @endconfig
                                @foreach($custom_port_descr as $custom_descr)
                                    <li><a href="{{ url('iftype/type=' . urlencode($custom_descr)) }}"><i class="fa fa-connectdevelop fa-fw fa-lg" aria-hidden="true"></i> {{ ucwords($custom_descr) }}</a></li>
                                @endforeach
                            @endif

                            <li role="presentation" class="divider"></li>

                            @if($port_counts['alerted'])
                                <li><a href="{{ url('ports/alerted=yes') }}"><i class="fa fa-exclamation-circle fa-fw fa-lg" aria-hidden="true"></i> Alerts ({{ $port_counts['alerted'] }})</a></li>
                            @endif

                            <li><a href="{{ url('ports/state=down') }}"><i class="fa fa-arrow-circle-down fa-fw fa-lg" aria-hidden="true"></i> Down ({{ $port_counts['down'] }})</a></li>
                            <li><a href="{{ url('ports/state=admindown') }}"><i class="fa fa-arrow-circle-o-down fa-fw fa-lg" aria-hidden="true"></i> Disabled ({{ $port_counts['shutdown'] }})</a></li>

                            @if($port_counts['deleted'])
                                <li><a href="{{ url('ports/deleted=yes') }}"><i class="fa fa-minus-circle fa-fw fa-lg" aria-hidden="true"></i> Deleted ({{ $port_counts['deleted'] }})</a></li>
                            @endif
                        @endif
                    </ul>
                </li>
{{-- Sensors --}}
                <li class="dropdown">
                    <a href="{{ url('health') }}" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-heartbeat fa-fw fa-lg fa-nav-icons hidden-md" aria-hidden="true"></i> <span class="hidden-sm">Health</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('health/metric=mempool') }}"><i class="fa fa-braille fa-fw fa-lg" aria-hidden="true"></i> Memory</a></li>
                        <li><a href="{{ url('health/metric=processor') }}"><i class="fa fa-microchip fa-fw fa-lg" aria-hidden="true"></i> Processor</a></li>
                        <li><a href="{{ url('health/metric=storage') }}"><i class="fa fa-database fa-fw fa-lg" aria-hidden="true"></i> Storage</a></li>

                        @foreach($sensor_menu as $sensor_menu_group)
                            @foreach($sensor_menu_group as $sensor_menu_entry)
                                @if($loop->first)
                                    <li role="presentation" class="divider"></li>
                                @endif
                                <li><a href="{{ url('health/metric=' . $sensor_menu_entry['class']) }}"><i class="fa fa-{{ $sensor_menu_entry['icon'] }} fa-fw fa-lg" aria-hidden="true"></i> {{ $sensor_menu_entry['descr'] }}</a></li>
                            @endforeach
                        @endforeach

                    </ul>
                </li>
{{-- Wireless --}}
                @if($wireless_menu->isNotEmpty())
                    <li class="dropdown">
                        <a href="{{ url('wireless') }}" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-wifi fa-fw fa-lg fa-nav-icons hidden-md" aria-hidden="true"></i> <span class="hidden-sm">Wireless</span></a>
                        <ul class="dropdown-menu">
                        @foreach($wireless_menu as $wireless_menu_entry)
                                <li><a href="{{ url('wireless/metric=' . $wireless_menu_entry->sensor_class) }}"><i class="fa fa-{{ $wireless_menu_entry->icon() }} fa-fw fa-lg" aria-hidden="true"></i> {{ $wireless_menu_entry->classDescr() }}</a></li>
                        @endforeach
                        </ul>
                    </li>
                @endif
{{-- App --}}
                @if($app_menu->isNotEmpty())
                    <li class="dropdown">
                        <a href="{{ url('apps') }}" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-tasks fa-fw fa-lg fa-nav-icons hidden-md" aria-hidden="true"></i> <span class="hidden-sm">Apps</span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('apps') }}"><i class="fa fa-object-group fa-fw fa-lg" aria-hidden="true"></i> Overview</a></li>
                            @foreach($app_menu as $app_type => $app_instances)
                                @if($app_instances->filter->app_instance->isNotEmpty())
                                    <li class="dropdown-submenu">
                                        <a href="{{ url('apps/app=' . $app_type) }}"><i class="fa fa-server fa-fw fa-lg" aria-hidden="true"></i> {{ $app_instances->first()->displayName() }}</a>
                                        <ul class="dropdown-menu scrollable-menu">
                                            @foreach($app_instances as $app_instance)
                                            <li><a href="{{ url("apps/app=$app_type/instance=$app_instance") }}"><i class="fa fa-angle-double-right fa-fw fa-lg" aria-hidden="true"></i> {{ ucfirst($app_instance->app_instance) }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li><a href="{{ url('apps/app=' . $app_type) }}"><i class="fa fa-angle-double-right fa-fw fa-lg" aria-hidden="true"></i> {{ $app_instances->first()->displayName() }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
{{-- Routing --}}
                @if($routing_menu)
                    <li class="dropdown">
                        <a href="{{ url('routing') }}" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-random fa-fw fa-lg fa-nav-icons hidden-md" aria-hidden="true"></i> <span class="hidden-sm">Routing</span></a>
                        <ul class="dropdown-menu">
                        @foreach($routing_menu as $routing_menu_group)
                            @if(!$loop->first)
                                <li role="presentation" class="divider"></li>
                            @endif
                            @foreach($routing_menu_group as $routing_menu_entry)
                                <li><a href="{{ url('routing/protocol=' . $routing_menu_entry['url']) }}"><i class="fa fa-{{ $routing_menu_entry['icon'] }} fa-fw fa-lg" aria-hidden="true"></i> {{ $routing_menu_entry['text'] }}</a></li>
                            @endforeach
                        @endforeach

                        @if($bgp_alerts)
                            <li role="presentation" class="divider"></li>
                            <li><a href="{{ url('routing/protocol=bgp/adminstatus=start/state=down') }}"><i class="fa fa-exclamation-circle fa-fw fa-lg" aria-hidden="true"></i> Alerted BGP ({{ $bgp_alerts }})</a></li>
                        @endif
                        @admin
                            @if($show_peeringdb)
                                <li role="presentation" class="divider"></li>
                                <li><a href="{{ url('peering') }}"><i class="fa fa-hand-o-right fa-fw fa-lg" aria-hidden="true"></i> PeeringDB</a></li>
                            @endif
                        @endadmin
                        </ul>
                    </li>
                @endif
{{-- Alerts --}}
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-exclamation-circle fa-col-{{ $alert_menu_class }} fa-fw fa-lg fa-nav-icons hidden-md" aria-hidden="true"></i> <span class="hidden-sm">Alerts</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('alerts') }}"><i class="fa fa-bell fa-fw fa-lg" aria-hidden="true"></i> Notifications</a></li>
                        <li><a href="{{ url('alert-log') }}"><i class="fa fa-file-text fa-fw fa-lg" aria-hidden="true"></i> Alert History</a></li>
                        <li><a href="{{ url('alert-stats') }}"><i class="fa fa-bar-chart fa-fw fa-lg" aria-hidden="true"></i> Statistics</a></li>
                        @admin
                        <li role="presentation" class="divider"></li>
                        <li><a href="{{ url('alert-rules') }}"><i class="fa fa-list fa-fw fa-lg" aria-hidden="true"></i> Alert Rules</a></li>
                        <li><a href="{{ url('alert-schedule') }}"><i class="fa fa-calendar fa-fw fa-lg" aria-hidden="true"></i> Scheduled Maintenance</a></li>
                        <li><a href="{{ url('templates') }}"><i class="fa fa-file fa-fw fa-lg" aria-hidden="true"></i> Alert Templates</a></li>
                        <li><a href="{{ url('alert-transports') }}"><i class="fa fa-bus fa-fw fa-lg" aria-hidden="true"></i> Alert Transports</a></li>
                        @endadmin
                    </ul>
                </li>
                @includeIf('menu.custom')
            </ul>

{{-- User --}}
            <form role="search" class="navbar-form navbar-right global-search">
                <div class="form-group">
                    <input class="form-control typeahead" type="search" id="gsearch" name="gsearch" placeholder="Global Search">
                </div>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-user fa-fw fa-lg fa-nav-icons" aria-hidden="true"></i>
                        <span class="visible-xs-inline-block">User</span><span class="badge badge-navbar-user count-notif {{ $notification_count ? 'badge-danger' : 'badge-default' }}">{{ $notification_count }}</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('preferences') }}"><i class="fa fa-cog fa-fw fa-lg" aria-hidden="true"></i> My Settings</a></li>
                        <li><a href="{{ url('notifications') }}"><span class="badge count-notif">{{ $notification_count }}</span> Notifications</a></li>
                        <li role="presentation" class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out fa-fw fa-lg" aria-hidden="true"></i> @lang('Logout')
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown" style="margin-left:5px"><i class="fa fa-cog fa-fw fa-lg fa-nav-icons" aria-hidden="true"></i> <span class="visible-xs-inline-block">Settings</span></a>
                    <ul class="dropdown-menu">
                        @admin
                        <li><a href="{{ url('settings') }}"><i class="fa fa-cogs fa-fw fa-lg" aria-hidden="true"></i> Global Settings</a></li>
                        <li><a href="{{ url('validate') }}"><i class="fa fa-check-circle fa-fw fa-lg" aria-hidden="true"></i> Validate Config</a></li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="{{ route('users.index') }}"><i class="fa fa-user-circle-o fa-fw fa-lg" aria-hidden="true"></i> Manage Users</a></li>
                        <li><a href="{{ url('authlog') }}"><i class="fa fa-shield fa-fw fa-lg" aria-hidden="true"></i> Auth History</a></li>
                        <li role="presentation" class="divider"></li>
                        <li class="dropdown-submenu">
                            <a href="{{ url('pollers') }}"><i class="fa fa-th-large fa-fw fa-lg" aria-hidden="true"></i> @lang('Pollers')</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('pollers/tab=pollers') }}"><i class="fa fa-th-large fa-fw fa-lg" aria-hidden="true"></i> @lang('Pollers')</a></li>
                                @config('distributed_poller')
                                <li><a href="{{ url('pollers/tab=groups') }}"><i class="fa fa-th fa-fw fa-lg" aria-hidden="true"></i> @lang('Groups')</a></li>
                                @endconfig
                                <li><a href="{{ url('pollers/tab=performance') }}"><i class="fa fa-th-large fa-fw fa-lg" aria-hidden="true"></i> @lang('Performance')</a></li>
                                <li><a href="{{ url('pollers/tab=log') }}"><i class="fa fa-file-text fa-fw fa-lg" aria-hidden="true"></i> @lang('History')</a></li>
                            </ul>
                        </li>
                        <li role="presentation" class="divider"></li>
                        <li class="dropdown-submenu">
                            <a href="#"><i class="fa fa-code fa-fw fa-lg" aria-hidden="true"></i> API</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('api-access') }}"><i class="fa fa-cog fa-fw fa-lg" aria-hidden="true"></i> API Settings</a></li>
                                <li><a href="https://docs.librenms.org/API/" target="_blank" rel="noopener"><i class="fa fa-book fa-fw fa-lg" aria-hidden="true"></i> API Docs</a></li>
                            </ul>
                        </li>
                        <li role="presentation" class="divider"></li>
                        @endadmin
                        @if (isset($refresh))
                        <li class="dropdown-submenu">
                            <a href="#"><span class="countdown_timer" id="countdown_timer"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#"><span class="countdown_timer_status" id="countdown_timer_status"></span></a></li>
                            </ul>
                        </li>
                        <li role="presentation" class="divider"></li>
                        @endif
                        <li><a href="{{ url('about') }}"><i class="fa fa-info-circle fa-fw fa-lg" aria-hidden="true"></i> About&nbsp;{{ \LibreNMS\Config::get('project_name') }}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    var devices = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "ajax_search.php?search=%QUERY&type=device",
            filter: function (devices) {
                return $.map(devices, function (device) {
                    return {
                        device_id: device.device_id,
                        device_image: device.device_image,
                        url: device.url,
                        name: device.name,
                        device_os: device.device_os,
                        version: device.version,
                        device_hardware: device.device_hardware,
                        device_ports: device.device_ports,
                        location: device.location
                    };
                });
            },
            wildcard: "%QUERY"
        }
    });
    var ports = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "ajax_search.php?search=%QUERY&type=ports",
            filter: function (ports) {
                return $.map(ports, function (port) {
                    return {
                        count: port.count,
                        url: port.url,
                        name: port.name,
                        description: port.description,
                        colours: port.colours,
                        hostname: port.hostname
                    };
                });
            },
            wildcard: "%QUERY"
        }
    });
    var bgp = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "ajax_search.php?search=%QUERY&type=bgp",
            filter: function (bgp_sessions) {
                return $.map(bgp_sessions, function (bgp) {
                    return {
                        count: bgp.count,
                        url: bgp.url,
                        name: bgp.name,
                        description: bgp.description,
                        localas: bgp.localas,
                        bgp_image: bgp.bgp_image,
                        remoteas: bgp.remoteas,
                        colours: bgp.colours,
                        hostname: bgp.hostname
                    };
                });
            },
            wildcard: "%QUERY"
        }
    });

    if ($(window).width() < 768) {
        var cssMenu = 'typeahead-left';
    } else {
        var cssMenu = '';
    }

    devices.initialize();
    ports.initialize();
    bgp.initialize();
    $('#gsearch').typeahead({
            hint: true,
            highlight: true,
            minLength: 1,
            classNames: {
                menu: cssMenu
            }
        },
        {
            source: devices.ttAdapter(),
            limit: '{{ $typeahead_limit }}',
            async: true,
            display: 'name',
            valueKey: 'name',
            templates: {
                header: '<h5><strong>&nbsp;Devices</strong></h5>',
                suggestion: Handlebars.compile('<p><a href="@{{url}}"><img src="@{{device_image}}" border="0"> <small><strong>@{{name}}</strong> | @{{device_os}} | @{{version}} | @{{device_hardware}} with @{{device_ports}} port(s) | @{{location}}</small></a></p>')
            }
        },
        {
            source: ports.ttAdapter(),
            limit: '{{ $typeahead_limit }}',
            async: true,
            display: 'name',
            valueKey: 'name',
            templates: {
                header: '<h5><strong>&nbsp;Ports</strong></h5>',
                suggestion: Handlebars.compile('<p><a href="@{{url}}"><small><i class="fa fa-link fa-sm icon-theme" aria-hidden="true"></i> <strong>@{{name}}</strong> – @{{hostname}}<br /><i>@{{description}}</i></small></a></p>')
            }
        },
        {
            source: bgp.ttAdapter(),
            limit: '{{ $typeahead_limit }}',
            async: true,
            display: 'name',
            valueKey: 'name',
            templates: {
                header: '<h5><strong>&nbsp;BGP Sessions</strong></h5>',
                suggestion: Handlebars.compile('<p><a href="@{{url}}"><small>@{{bgp_image}} @{{name}} - @{{hostname}}<br />AS@{{localas}} -> AS@{{remoteas}}</small></a></p>')
            }
        }).on('typeahead:select', function (ev, suggestion) {
            window.location.href = suggestion.url;
        }).on('keyup', function (e) {
            // on enter go to the first selection
            if (e.which === 13) {
                $('.tt-selectable').first().click();
            }
        });
</script>
