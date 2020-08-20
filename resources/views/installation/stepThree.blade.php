@extends('installation.master')

@section('content')
<div class="col-lg-8 col-md-12">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> Administrator Account Info </strong></h4>
            <p class="card-category">Ente information for the administrator account.</p>
        </div>
        <div class="card-body">
            <form action="{{ route('installation.stepThree') }}" method="POST">
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
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" >
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Password</label>
                            <input type="password" name="password" class="form-control" >
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" >
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-block btn-primary">Install</button>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
@endsection