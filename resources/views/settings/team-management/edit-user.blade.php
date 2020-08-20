@extends('master')

@section('page_title')
Team Management
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Edit User </strong></h4>
        <p class="card-category">Edit user credentials</p>
    </div>
    <div class="card-body">
        <form action="{{ route('editUser', $id) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">First Name</label>
                        <input type="text" name="first_name" value="{{ $user->detail->first_name }}" class="form-control" >
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Last Name</label>
                    <input type="text" name="last_name" value="{{ $user->detail->last_name }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" >
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Store To Manage</label>
                        @foreach($stores as $store)
                        <ul>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="store[]" value="{{ $store->id }}" {{ ($user->accessRight->contains('store_id', $store->id)) ? 'checked' : '' }}>
                                    {{ $store->name }}
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </ul>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('teamManagement') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Change Password </strong></h4>
        <p class="card-category">Edit password credential</p>
    </div>
    <div class="card-body">
        <form action="{{ route('editUser', ['id' => $id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Old Password</label>
                        <input type="password" name="old_password" class="form-control" >
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">New Password</label>
                        <input type="password" name="new_password" class="form-control" >
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" >
                    </div>
                </div>
            </div>
            
            <button type="submit" name="update_password" class="btn btn-primary">Save</button>
            <a href="{{ route('teamManagement') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection