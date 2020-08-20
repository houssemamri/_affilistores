<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ ($site['favicon'] !== '') ? asset('img/uploads/' . $site['favicon']) : asset('img/uploads/' . $site['logo']) }}">
    <title>{{ ucwords($site['site_name']) }} </title>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font-awesome.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/material-dashboard.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}" />
</head>
<body>
    <div class="wrapper">
        <div class="container login-wrapper">
            <div class="col-lg-12 {{ count($stores) <= 6 ? 'login-container' : '' }}">
                <div class="card">
                    <div class="card-header card-header-primary " id="store-roof">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="pull-left">
                                    <h4 class="card-title ">Affiliate Stores <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="You can manage here your stores. Can add, edit, delete, and see stats of your store.">info</i></h4>
                                    <p class="card-category"> Select A Store To Manage. <u><a href="{{ route('store.owndomain') }}">You want to host your store on your own domain?</a></u></p>
                                    </div>
                                    <div class="pull-right">
                                        <a data-toggle="modal" data-target="{{ $countStoreThisMonth < $limit ? '#createStore' : '#limitStore' }}" href="#" class="pull-right btn btn-warning">Create Affiliate Store</a>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($stores as $store)
                            <div class="col-md-4 col-sm-6">
                                <div class="card card-stats">
                                    <div class="card-header card-header-warning card-header-icon">
                                        <div class="card-icon">
                                            <i class="material-icons">store</i>
                                        </div>
                                        <p class="card-category">{{ $store->url }}</p>
                                        <h3 class="card-title"><strong>{{ $store->name }}</strong></h3>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <div class="btn-group store-icon-group" role="group" aria-label="Basic example">
                                                <a class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Manage" href="{{ route('dashboard', $store->subdomain) }}"><i class="material-icons store-icons">settings</i></a>
                                                <a class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Stats" href="{{ route('reports.index', $store->subdomain) }}"><i class="material-icons store-icons">bar_chart</i></a>
                                                <a class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Edit" href="#" onclick="editStore('{{ addslashes(json_encode($store)) }}')"><i class="material-icons store-icons">edit</i></a>
                                                <a class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Delete" href="#" onclick="confirmDelete({{ $store->id }})"><i class="material-icons store-icons">delete</i></a>
                                                
                                                @if($store->status == 1)
                                                <a class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Go Offline" href="{{ route('store.changeStatus', [Crypt::encrypt($store->id), 'offline']) }}"><i class="material-icons store-icons">visibility_off</i></a>
                                                @else
                                                <a class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Go Online" href="{{ route('store.changeStatus', [Crypt::encrypt($store->id), 'online']) }}" ><i class="material-icons store-icons">visibility</i></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
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
                                        <img src="{{ asset('img/uploads/' . $site['logo']) }}" class="img-fluid" alt="">
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
                                                        <input type="text" name="subdomain" id="setFirstSubdomain" class="form-control">
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

            <div class="modal fade" id="createStore" tabindex="-1" role="dialog" aria-labelledby="setupStore" aria-hidden="true" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('createStore') }}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="modal-body">
                                <div class="card create-store">
                                    <div class="card-header card-header-primary">
                                        <h4 class="card-title">Create Affiliate Store</h4>
                                        <p class="card-category">Create a new affiliate store here</p>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('createStore') }}" method="POST" enctype="multipart/form-data">
                                            {!! csrf_field() !!}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="bmd-label-floating">Subdomain Name</label>
                                                        <div class="form-group">
                                                            <div class="input-group mb-3">
                                                            <input type="text" name="subdomain" id="createSubdomain" class="form-control">
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
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="bmd-label-floating">Store Logo</label>
                                                        <label for="add-logo" class="btn btn-info">Add logo</label>
                                                    </div>
                                                    <div class="form-group logo-sample">
                                                        <img src="" alt="" class="img-fluid" id="add-store-logo">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="file" name="logo" accept="image/*" class="form-control-file" id="add-logo" >
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
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editStore" tabindex="-1" role="dialog" aria-labelledby="editStore" aria-hidden="true" >
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
                                                        <input type="text" name="subdomain" id="editSubdomain" class="form-control">
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

            <div class="modal fade" id="limitStore" tabindex="-1" role="dialog" aria-labelledby="editStore" aria-hidden="true" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="card edit-store">
                                <div class="card-header card-header-danger text-center">
                                    <h4 class="card-title">You have reached the maximum number of store this month.</h4>

                                    <button type="button" class="btn btn-default" data-dismiss="modal">Okay</button>

                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script src="{!! asset('js/popper.min.js') !!}"></script>
    <script src="{!! asset('js/bootstrap-material-design.js') !!}"></script>
    <script src="{!! asset('js/perfect-scrollbar.jquery.min.js') !!}"></script>
    <!--  Charts Plugin, full documentation here: https://gionkunz.github.io/chartist-js/ -->
    <script src="{!! asset('js/chartist.min.js') !!}"></script>
    <!-- Library for adding dinamically elements -->
    <script src="{!! asset('js/arrive.min.js') !!}" type="text/javascript"></script>
    <!--  Notifications Plugin, full documentation here: http://bootstrap-notify.remabledesigns.com/    -->
    <script src="{!! asset('js/bootstrap-notify.js') !!}"></script>
    <!-- Material Dashboard Core initialisations of plugins and Bootstrap Material Design Library -->
    <script src="{!! asset('js/material-dashboard.js?v=2.0.0') !!}"></script>

    @include('extra.alerts')

    @if(count($stores) == 0)
        <script> $('#setupStoreModal').modal() </script>
    @endif

    <script>
         function createPermalink(permalink){
            permalink = permalink.split(' ').join('-');
            permalink = permalink.replace(/[^a-z0-9\s]/gi, '-');
            permalink = permalink.split('_').join('-');
            permalink = permalink.replace(/-{2,}/g,'-');
            permalink = permalink.toLowerCase().trim();

            return permalink;
        }
        
        $('[data-toggle="tooltip"]').tooltip()

        $('#setFirstSubdomain').on('keypress', function(event){
            var regex = new RegExp("^[a-zA-Z0-9\-_\b]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
           
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });

        $('#setFirstSubdomain').on('keyup', function(event){
            var permalink = createPermalink($(this).val());
            $('#setFirstSubdomain').val(permalink);
        });

        $('#createSubdomain').on('keypress', function(event){
            var regex = new RegExp("^[a-zA-Z0-9\-_\b]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
           
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });

        $('#createSubdomain').on('keyup', function(event){
            var permalink = createPermalink($(this).val());
            $('#createSubdomain').val(permalink);
        });

        $('#editSubdomain').on('keypress', function(event){
            var regex = new RegExp("^[a-zA-Z0-9\-_\b]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
           
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });

        $('#editSubdomain').on('keyup', function(event){
            var permalink = createPermalink($(this).val());
            $('#editSubdomain').val(permalink);
        });

        function editStore(store) {
            var store = jQuery.parseJSON(store);
            var logoPath = "{{ asset('img/uploads/logo') }}" + "/";
            
            $('#edit-store-id').val(store.id);
            $('#editSubdomain').val(store.subdomain);
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
       function readURL(input, element) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $(element).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#logo").change(function(){
            readURL(this, '#store-logo');
        });

        $("#add-logo").change(function(){
            readURL(this, '#add-store-logo');
        });
    </script>
</html>

