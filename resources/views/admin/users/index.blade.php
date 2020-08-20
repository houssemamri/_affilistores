@extends('admin.master')

@section('page_title')
Users
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Users </strong></h4>
                <p class="card-category">Manage your users</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('users.add') }}" class="pull-right btn btn-warning">Create New User</a>
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
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   
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
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
