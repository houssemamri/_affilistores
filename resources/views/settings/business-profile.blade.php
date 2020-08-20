@extends('master')

@section('page_title')
Store Business Profile
@endsection

@section('content')
<form action="{{ route('settings.businessProfile', Session::get('subdomain')) }}" method="POST" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> Business Profile <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Profile that will be displayed on Contact Page">info</i></strong></h4>
            <p class="card-category">Edit your profile for your store</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Address</label>
                    <input type="text" name="address" value="{{ $businessProfile->address }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Email</label>
                        <input type="email" name="email" value="{{ $businessProfile->email }}" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Phone</label>
                    <input type="text" name="phone" value="{{ $businessProfile->phone }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">City</label>
                        <input type="text" class="form-control" name="city" value="{{ $businessProfile->city }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Country</label>
                        <select class="form-control" name="country" id="country">
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">State</label>
                        <select class="form-control" name="state" id="state">
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('dashboard', ['subdomain', Session::get('subdomain')]) }}"class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
        </div>
    </div>
</form>

@endsection

@section('custom-scripts')
    <script src="{{ asset('js/countries.js') }}"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip()

        populateCountries("country", "state"); 
        
        var country = "{{ $businessProfile->country }}";
        var state = "{{ $businessProfile->state }}";
        
        if(country != '')
            $('#country').val(country).trigger('change');
        

        if(state != '')
            $('#state').val(state).trigger('state');
        
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
