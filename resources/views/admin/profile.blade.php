@extends('admin.master')

@section('page_title')
Profile
@endsection

@section('content')
<form action="{{ route('admin.updateProfile') }}" method="POST" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> Personal </strong></h4>
            <p class="card-category">Edit your profile to update your personal information</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group logo-sample text-center">
                        <img src="{{ asset('img/uploads/avatar/'.$user->detail->avatar) }}" alt="" class="img-fluid" id="avatar-img" style="{{ ($user->detail->avatar) ? 'display:block;' : 'display:none;' }}">
                        <i class="material-icons account-avatar" style="{{ ($user->detail->avatar) ? 'display:none;' : 'display:block;' }}">account_box</i>

                        <input type="file" name="avatar" accept="image/*" class="form-control-file" id="avatar" >
                        <label for="avatar" class="btn btn-block btn-info">{{ isset($user->detail->avatar) ? 'Change' : 'Add' }}  Profile Picture</label>
                    </div>
                </div>
                <div class="col-lg-8 center">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">First Name</label>
                                <input type="text" name="first_name" value="{{ $user->detail->first_name }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Last Name</label>
                            <input type="text" name="last_name" value="{{ $user->detail->last_name }}" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="clearfix"></div>
        </div>
        <div class="card-header card-header-primary card-margin-top">
            <h4 class="card-title"><strong> Contact Info</strong></h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Address</label>
                    <input type="text" name="address" value="{{ $user->detail->address }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" disabled>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Phone</label>
                    <input type="text" name="phone" value="{{ $user->detail->phone }}" class="form-control" >
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
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">City</label>
                        <input type="text" class="form-control" name="city">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.dashboard') }}"class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
        </div>
    </div>
</form>
<form action="{{ route('admin.updatePassword') }}" method="POST">
    {!! csrf_field() !!}
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> Change Password </strong></h4>
            <p class="card-category">Update your password</p>
        </div>
        <div class="card-body">
            <div class="row">
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
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Password</label>
                        <input type="password" name="password" class="form-control" >
                    </div>
                </div>
                
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.dashboard') }}"class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
        </div>
    </div>
   
</form>

@endsection

@section('custom-scripts')
    <script src="{{ asset('js/countries.js') }}"></script>
    <script>
        populateCountries("country", "state"); 
        
        var country = "{{ $user->detail->country }}";
        var state = "{{ $user->detail->state }}";
        
        $('#country')
            .val(country)
            .trigger('change');

        $('#state')
            .val(state)
            .trigger('state');
        
    </script>

    <script>
       function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $('#avatar-img').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#avatar").change(function(){
            $('.account-avatar').hide();
            $('#avatar-img').show();
            
            readURL(this);
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
