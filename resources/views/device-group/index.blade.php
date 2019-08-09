@extends('layouts.librenmsv1')

@section('title', __('Device Groups'))

@section('content')
    <div class="container-fluid">
        <div id="manage-device-groups-panel" class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa fa-th fa-fw fa-lg" aria-hidden="true"></i> @lang('Device Groups')
                </h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <a type="button" class="btn btn-primary" href="{{ route('device-groups.create') }}">
                            <i class="fa fa-plus"></i> @lang('New Device Group')
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="manage-device-groups-table" class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Description')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Devices')</th>
                            <th>@lang('Pattern')</th>
                            <th>@lang('Actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($device_groups as $device_group)
                            <tr id="row_{{ $device_group->id }}">
                                <td>{{ $device_group->name }}</td>
                                <td>{{ $device_group->desc }}</td>
                                <td>{{ __(ucfirst($device_group->type)) }}</td>
                                <td>
                                    <a href="{{ url("/devices/group=$device_group->id") }}">{{ $device_group->devices_count }}</a>
                                </td>
                                <td>{{ $device_group->type == 'dynamic' ? $device_group->getParser()->toSql(false) : '' }}</td>
                                <td>
                                    <a type="button" class="btn btn-primary btn-sm" aria-label="@lang('Edit')"
                                       href="{{ route('device-groups.edit', $device_group->id) }}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <button type="button" class="btn btn-danger btn-sm" aria-label="@lang('Delete')"
                                            onclick="delete_dg(this, '{{ $device_group->name }}', '{{ route('device-groups.destroy', $device_group->id) }}')">
                                        <i
                                            class="fa fa-trash" aria-hidden="true"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function delete_dg(button, name, url) {
            var index = button.parentNode.parentNode.rowIndex;

            if (confirm('@lang('Are you sure you want to delete ')' + name + '?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function (msg) {
                        document.getElementById("manage-device-groups-table").deleteRow(index);
                        toastr.success(msg);
                    },
                    error: function () {
                        toastr.error('@lang('The device group could not be deleted')');
                    }
                });
            }

            return false;
        }
    </script>
@endsection

@section('css')
    <style>
        .table-responsive {
            padding-top: 16px
        }
    </style>
@endsection
