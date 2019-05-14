@extends('layouts.librenmsv1')

@section('title', __('Manage Users'))

@section('content')
<div class="container-fluid">
    <div id="manage-users-panel" class="panel panel-default">
        <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-user-circle-o fa-fw fa-lg" aria-hidden="true"></i> @lang('Manage Users')</h4></div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="users" class="table table-bordered table-condensed" style="display: none;">
                    <thead>
                    <tr>
                        <th data-column-id="user_id" data-visible="false" data-identifier="true" data-type="numeric">@lang('ID')</th>
                        <th data-column-id="username">@lang('Username')</th>
                        <th data-column-id="realname">@lang('Real Name')</th>
                        <th data-column-id="level" data-formatter="level" data-type="numeric">@lang('Access')</th>
                        <th data-column-id="auth_type" data-visible="{{ $multiauth ? 'true' : 'false' }}">@lang('Auth')</th>
                        <th data-column-id="email">@lang('Email')</th>
                        <th data-column-id="descr">@lang('Description')</th>
                        <th data-column-id="action" data-formatter="actions" data-sortable="false" data-searchable="false">@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody id="users_rows">
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->user_id }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->realname }}</td>
                                <td>{{ $user->level }}</td>
                                <td>{{ $user->auth_type }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->descr }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script type="application/javascript">
        $(document).ready(function(){
            var user_grid = $("#users");
            user_grid.bootgrid({
                formatters: {
                    actions: function (column, row) {
                        var edit_button = '<form action="users/' + row['user_id'] + '/edit" method="GET">' +
                            '<button type="submit" title="@lang('Edit')" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></button>' +
                            '</form> ';

                        var delete_button = '<button type="button" title="@lang('Delete')" class="btn btn-sm btn-danger" onclick="return delete_user(' + row['user_id'] + ', \'' + row['username'] + '\');">' +
                            '<i class="fa fa-trash"></i></button> ';

                        var manage_button = '<form action="edituser/" method="GET"';

                        if (row['level'] >= 5) {
                            manage_button += ' style="visibility:hidden;"'
                        }

                        manage_button += '><input type="hidden" name="user_id" value="' + row['user_id'] +
                            '"><button type="submit" title="@lang('Manage Access')" class="btn btn-sm btn-primary"><i class="fa fa-tasks"></i></button>' +
                            '</form> ';

                        var output = manage_button + edit_button;
                        if ('{{ Auth::id() }}' != row['user_id']) {
                            output += delete_button;
                        }

                        return output
                    },
                    level: function (column, row) {
                        var level = row[column.id];
                        if (level == 10) {
                            return '@lang('Admin')';
                        } else if (level == 5) {
                            return '@lang('Global Read')';
                        } else if (level == 11) {
                            return '@lang('Demo')';
                        }

                        return '@lang('Normal')';
                    }
                }
            });

            @if(\LibreNMS\Authentication\LegacyAuth::get()->canManageUsers())
                $('.actionBar').append('<div class="pull-left"><a href="users/create" type="button" class="btn btn-primary">@lang('Add User')</a></div>');
            @endif

            user_grid.css('display', 'table'); // done loading, show
        });

        function delete_user(user_id, username)
        {
            if (confirm('@lang('Are you sure you want to delete ')' + username + '?')) {
                $.ajax({
                    url: 'users/' + user_id,
                    type: 'DELETE',
                    success: function (msg) {
                        $("#users").bootgrid("remove", [user_id]);
                        toastr.success(msg);
                    },
                    error: function () {
                        toastr.error('@lang('The user could not be deleted')');
                    }
                });
            }

            return false;
        }
    </script>
@endsection

@section('css')
<style>
    #manage-users-panel .panel-title { font-size: 18px; }
    #users form { display:inline; }
</style>
@endsection
