@extends('installation.master')

@section('content')
<div class="col-lg-8 col-md-12">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> Domain & Database Configuration </strong></h4>
            <p class="card-category">Enter database configrations below</p>
        </div>
        <div class="card-body">
            <form action="{{ route('installation.stepOne') }}" method="POST">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="bmd-label-floating">Site Name</label>
                            <input type="text" name="site_name" value="{{ old('site_name') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="bmd-label-floating">Domain</label>
                            <input type="text" name="database_password" value="{{ $_SERVER['HTTP_HOST'] }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Database Host</label>
                            <input type="text" name="database_host" value="{{ 'localhost' }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Database Name</label>
                            <input type="text" name="database_name" value="{{ old('database_name') }}" class="form-control" >
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Database Username</label>
                            <input type="text" name="database_username" value="{{ old('database_username') }}" class="form-control" >
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Database Password</label>
                            <input type="text" name="database_password" value="{{ old('database_password') }}" class="form-control" >
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-block btn-primary">Next</button>
                <div class="clearfix"></div>

            </form>
        </div>
    </div>
</div>
@endsection