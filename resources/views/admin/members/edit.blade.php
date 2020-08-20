@extends('admin.master')

@section('page_title')
Edit Member
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Edit Member Member </strong></h4>
        <p class="card-category">Edit your members credentials</p>
    </div>
    <div class="card-body">
        <form action="{{ route('members.edit', $id) }}" method="POST">
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
            </div>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Membership</label>
                        <select class="form-control" name="membership">
                            @foreach($memberships as $membership)
                            <option value="{{ $membership->id }}" {{ (isset($user->memberDetail) && $membership->id == $user->memberDetail->membership_id) ? 'selected' : '' }}>{{ $membership->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group is-focused">
                        <label class="bmd-label-floating">Expiry Date</label>
                        <input type="date" name="expiry_date" value="{{ isset($user->memberDetail->expiry_date) ? date_format(date_create($user->memberDetail->expiry_date), 'Y-m-d') : '' }}" class="form-control" {{ isset($user->memberDetail->expiry_date) ? '' : 'disabled' }}>
                    </div>

                    <div class="form-check form-check-inline ">
                        <label class="form-check-label">
                            <input class="form-check-input" name="never_expire" type="checkbox" {{ isset($user->memberDetail->expiry_date) ? '' : 'checked' }}>
                            Check if never expires
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('members.index') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Change Password </strong></h4>
        <p class="card-category">Update member password</p>
    </div>
    <div class="card-body">
        <form action="{{ route('members.edit', $id) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">New Password</label>
                        <input type="password" name="new_password" class="form-control" >
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" >
                    </div>
                </div>
            </div>
            
            <button type="submit" name="update_password" class="btn btn-primary">Save</button>
            <a href="{{ route('members.index') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection

@section('custom-scripts')
    <script>
        $('input[name=never_expire]').change(function(){
            if($(this).is(":checked")){
                $('input[name=expiry_date]').attr('disabled', 'disabled');
            }else{
                $('input[name=expiry_date]').removeAttr('disabled');
            }
        });
    </script>
@endsection