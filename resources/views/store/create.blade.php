@extends('master')

@section('page_title')
    Create Affliliate Store
@endsection

@section('content')
<div class="col-lg-12">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title">Create Affiliate Store</h4>
            <p class="card-category">Create a new affiliate store here</p>
        </div>
        <div class="card-body">
            <form action="{{ route('createStore') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="bmd-label-floating">Subdomain Name</label>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                <input type="text" name="subdomain" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">.{{ $domain }}/</span>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="bmd-label-floating">Store Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="bmd-label-floating">Store Logo</label>
                            <label for="logo" class="btn btn-info">Add logo</label>
                        </div>
                        <div class="form-group logo-sample">
                            <img src="" alt="" class="img-fluid" id="store-logo">
                        </div>
                        <div class="form-group">
                            <input type="file" name="logo" accept="image/*" class="form-control-file" id="logo" >
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('listStore', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('custom-scripts')
    <script>
       function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $('#store-logo').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#logo").change(function(){
            readURL(this);
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
