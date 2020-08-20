@extends('master')

@section('page_title')
Team Management
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Add Team Member </strong></h4>
        <p class="card-category">See & Edit your team</p>
    </div>
    <div class="card-body">
        <form action="{{ route('register') }}" method="POST">
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
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="bmd-label-floating">User Level</label>
                    <input type="email" name="user_level" value="{{ old('user_level') }}" class="form-control" >
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="bmd-label-floating">Access To</label>
                        <select name="access_to" id="" class="form-control">
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <a href="#" class="btn btn-primary">
                            <i class="material-icons">local_hospital</i>
                            Add more
                        </a>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="#" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>
@endsection