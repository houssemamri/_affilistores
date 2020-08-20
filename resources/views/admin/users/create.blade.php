@extends('admin.master')

@section('page_title')
Add New User
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Add New User </strong></h4>
        <p class="card-category">Add your new user</p>
    </div>
    <div class="card-body">
        <form action="{{ route('addUser') }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" >
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Access Rights</label>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" name="users" type="checkbox" value="">
                            Users
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" name="memberships" type="checkbox" value="">
                            Memberships
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            
            
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('users.index') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script>

    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection