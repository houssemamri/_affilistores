@extends('master')

@section('page_title')
    Affiliate Stores
@endsection

@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-lg-8 col-sm-12">
                        <h4 class="card-title ">Affiliate Stores</h4>
                        <p class="card-category"> Get complete information about all affiliate stores created.</p>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <a href="{{ route('createStore') }}" class="pull-right btn btn-warning">Create Affiliate Store</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            @foreach($stores as $store)
                                <tr>
                                    <td width="20%"><img src="{{ asset('img/uploads/logo/'.$store->logo.'') }}" class="img-fluid"></td>
                                    <td>
                                        <h5><strong>{{ $store->name }}</strong></h5>
                                        <p>{{ $store->url }}</p>
                                        {{ date_format($store->created_at, 'F d, Y @ H:i a') }}
                                    </td>
                                    <td class="text-center">
                                        Posted Products<br>
                                        0
                                    </td>
                                    <td class="text-center">
                                        Products Hits<br>
                                        0
                                    </td>
                                    <td class="text-center">
                                        Affiliate Hits<br>
                                        0
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('dashboard', ['subdomain' => $store->subdomain]) }}" class="btn btn- btn-primary">Manage</a>
                                            <a href="#" onclick="editStore('{{ json_encode($store) }}')" class="btn btn btn-success">Edit</a>
                                            <a href="#" class="btn btn- btn-info">View Stats</a>
                                            <a href="#" onclick="confirmDelete({{ $store->id }})" class="btn btn- btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="setupStoreModal" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <form action="{{ route('createStore') }}" method="POST">
            {!! csrf_field() !!}
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <img src="{{ asset('img/logo.png') }}" class="img-fluid" alt="">
                                <h4 class="card-title"><strong> Hi, {{ Auth::user()->name }} </strong></h4>
                                <p class="card-category">Create yout first business store!</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Store Name</label>
                                            <input type="text" name="name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
        <form action="{{ route('deleteStore') }}" method="POST">
            {!! csrf_field() !!}
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title"><strong> Are you sure to delete this store? </strong></h4>
                                <p class="card-category">Deleting store will delete all files related to it.</p>
                            </div>
                            <div class="card-body">
                                <div class="pull-right">
                                    <input type="hidden" value="" name="store_id" id="store_id">
                                    <button type="submit" class="btn btn-primary">Yes</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="editStore" tabindex="-1" role="dialog" aria-labelledby="setupStore" aria-hidden="true" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('editStore') }}" method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                        <div class="card edit-store">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Edit Store</h4>
                                <p class="card-category">Make changes in your store</p>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Subdomain Name</label>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text" name="subdomain" id="subdomain" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.{{ $domain }}/</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Store Name</label>
                                        <input type="text" name="name" id="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Store Logo</label>
                                        <label for="logo" class="btn btn-info">Change logo</label>
                                    </div>
                                    <div class="form-group logo-sample">
                                        <img src="" alt="" class="img-fluid" id="store-logo">
                                    </div>
                                    <div class="form-group">
                                        <input type="file" name="logo" accept="image/*" class="form-control-file" id="logo" >
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="pull-right">
                                    <input type="hidden" id="edit-store-id" name="store_id">
                                    <button type="submit" class="btn btn-primary">Yes</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>  
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    @if(count($stores) == 0)
        <script> $('#setupStoreModal').modal() </script>
    @endif

    <script>
        function editStore(store) {
            var store = jQuery.parseJSON(store);
            var logoPath = "{{ asset('img/uploads/logo') }}" + "/";
            
            $('#edit-store-id').val(store.id);
            $('#subdomain').val(store.subdomain);
            $('#name').val(store.name);
            $('#store-logo').attr('src', logoPath + store.logo);

            $('#editStore').modal();
        }

        function confirmDelete($store_id) {
            $('#deleteConfirmation').modal();
            $('#store_id').val($store_id);
        }
        

        $("#editStore").on("hidden.bs.modal", function () {
            $('#store_id').val('');
            $('#subdomain').val('');
            $('#name').val('');
            $('#store-logo').attr('src', '');
        });

        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#store_id').val('');
        });
    </script>

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
