@extends('subadmin.master')

@section('page_title')
Members
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Members </strong></h4>
                <p class="card-category">Manage your members</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('subadmin.members.add') }}" class="pull-right btn btn-warning">Create New Member</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table member-table" id="category-list">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Membership</th>
                        <th>Status</th>
                        <th>Sign Up</th>
                        <th>Last Login</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members->chunk(100) as $members)
                        @foreach($members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->memberDetail->membership->title }}</td>
                                <td>{!! ($member->active) ? "<span class='badge badge-pill badge-success'>Active</span>" : "<span class='badge badge-pill badge-dark'>Suspended</span>" !!}</td>
                                <td>{{ date_format(date_create($member->created_at ), 'Y-m-d') }}</td>
                                <td>{{ isset($member->last_login) ? $member->last_login : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        @if($member->active)
                                            <a class="btn btn-sm btn-default" href="{{ route('members.changeStatus', ['id' => Crypt::encrypt($member->id), 'status' => 'suspend']) }}">Suspend</a>
                                        @else
                                            <a class="btn btn-sm btn-default" href="{{ route('members.changeStatus', ['id' => Crypt::encrypt($member->id), 'status' => 'activate']) }}">Activate</a>
                                        @endif
                                        <a class="btn btn-sm btn-default" href="{{ route('members.edit', Crypt::encrypt($member->id)) }}" onclick="">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($member->id) }}')">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#category-list').DataTable();
        } );

        function confirmDelete(member_id) {
            $('#deleteConfirmation').modal();
            $('#member_id').val(member_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#member_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
