@extends('master')

@section('page_title')
Products
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Products </strong></h4>
                <p class="card-category">Manage your products</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('products.create.amazon', Session::get('subdomain')) }}" class="pull-right btn btn-warning btn-sm">Add Amazon Product Manually</a>
                        <a href="#" data-toggle="modal" data-target="#chooseMarket" class="pull-right btn btn-warning btn-sm">Add New Product</a>
                        <a href="#" class="pull-right btn btn-warning btn-sm btn-delete-products">Delete Checked Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table " id="product-list">
                <thead class="">
                    <tr>
                        <th></th>
                        <th>Product Id</th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Date Posted</th>
                        <th class="text-center">Publish date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td width="5%"></td>
                            <td width="10%">{{ $product->reference_id }}</td>
                            <td clas="text-center" width="15%">{!! $product->name !!}</td>
                            <td class="text-center"  width="10%"><img src="{{ strpos( $product->image, 'https') !== false ? $product->image :  str_replace('http', 'https', $product->image) }}" alt="" width="30%" class="img-fluid"></td>
                            <td class="text-center"><h4><strong>{{ currency($product->currency) }} {{ $product->price }}</strong></h4></td>
                            <td class="text-center">
                                @foreach($product->categories as $product_cat)
                                    @if(isset($product_cat->category->name))
                                    <span class="badge badge-info">
                                        {{ $product_cat->category->name }}
                                    </span>
                                    @endif
                                @endforeach
                            </td>
                            <td class="text-center">
                                @if(strcasecmp('amazon', $product->source) == 0)
                                    <img src="{{ asset('img/amazon-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                @elseif(strcasecmp('aliexpress', $product->source) == 0)
                                    <img src="{{ asset('img/aliexpress-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                @elseif(strcasecmp('ebay', $product->source) == 0)
                                    <img src="{{ asset('img/ebay-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                @elseif(strcasecmp('walmart', $product->source) == 0)
                                    <img src="{{ asset('img/walmart-icon.jpg') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                @elseif(strcasecmp('shopcom', $product->source) == 0)
                                    <img src="{{ asset('img/shopcom-icon.png') }}" alt="{{ $product->source }}" width="80" class="img-fluid">
                                @elseif(strcasecmp('cjcom', $product->source) == 0)
                                    <img src="{{ asset('img/cjcom-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                @elseif(strcasecmp('jvzoo', $product->source) == 0)
                                    <img src="{{ asset('img/jvzoo-icon.png') }}" alt="{{ $product->source }}" width="60" class="img-fluid">
                                @elseif(strcasecmp('clickbank', $product->source) == 0)
                                    <img src="{{ asset('img/clickbank-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                @elseif(strcasecmp('warriorplus', $product->source) == 0)
                                    <img src="{{ asset('img/warriorplus-icon.png') }}" alt="{{ $product->source }}" width="60" class="img-fluid">
                                @elseif(strcasecmp('paydotcom', $product->source) == 0)
                                    <img src="{{ asset('img/paydotcom-icon.png') }}" alt="{{ $product->source }}" width="60" class="img-fluid">
                                @endif
                            </td>
                            <td class="text-center">{{ date_format($product->created_at, 'm/d/Y') }}</td>
                            <td class="text-center">{{ date_format(date_create($product->published_date), 'm/d/Y H:i a') }}</td>
                            <td class="text-center">{!! ($product->published_date > Carbon\Carbon::now()) ? "<span class='badge badge-pill badge-warning'>Scheduled</span>" : "<span class='badge badge-pill badge-success'>Published</span>" !!}</td>
                            <td class="text-center">
                                <div class="btn-group-vertical" role="group" aria-label="Basic example">
                                    <a href="{{ route('products.edit', ['id' => Crypt::encrypt($product->id), 'subdomain' => Session::get('subdomain') ]) }}" class="btn btn-block btn-default">
                                        <i class="material-icons" data-toggle="tooltip" data-placement="right" title="Add and Edit product informations">create</i>
                                    </a>
                                    <a href="{{ route('index.product.show', ['subdomain' => $product->store->subdomain, 'permalink' => $product->permalink]) }}" target="_blank" class="btn btn-block btn-default">
                                        <i class="material-icons"  data-toggle="tooltip" data-placement="right" title="Visit product page">pageview</i>
                                    </a>
                                    <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($product->id) }}')" class="btn btn-block btn-default">
                                        <i class="material-icons"  data-toggle="tooltip" data-placement="right" title="Delete / Remove product from your store">delete</i>
                                    </a>
                                    <a href="{{ route('products.reviews', ['id' => Crypt::encrypt($product->id), 'subdomain' => Session::get('subdomain') ]) }}" class="btn btn-block btn-default">
                                        <span class='badge badge-danger comment-badge'> {{ $product->reviews->where('approved', 0)->count() }} </span><i class="material-icons"  data-toggle="tooltip" data-placement="right" title="View product's customer reviews and approve / disapprove them">comment</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="chooseMarket" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header card-header-primary ">
                        <div class="card-icon card-header-warning">
                            <i class="material-icons">add_shopping_cart</i>
                        </div>
                        <h3 class="card-title">Add Product</h3>
                    </div>
                    <div class="card-body text-center">
                        <h4>Choose among the world's top {{ count($affiliates) }} eCommerce sites to get your products</h4>
                        <div class="row">
                            @foreach($affiliates as $affiliate)
                                <div class="col-lg-4 flex">
                                    {{-- <a href="{{ route('products.create', ['source' => $affiliate, 'subdomain' => Session::get('subdomain') ]) }}" > --}}
                                    <a href="#" >

                                        <img src="{{ asset('img/' . $affiliate . '-icon.png') }}" alt="" class="img-fluid">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('products.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this product? </strong></h4>
                            <p class="card-category">Deleting this product will remove related data</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="product_id" id="product_id">
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

<div class="modal fade" id="deleteProducts" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('products.delete.multiple', Session::get('subdomain')) }}" method="POST" id="deleteProductForm">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete <span class="no_products"></span> products? </strong></h4>
                            <p class="card-category">Deleting products will delete all data related to it.</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="deleteProducts" id="delProdIds">
                                <button type="submit" class="btn btn-primary btn-delete">Yes</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="selectProducts" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Select atleast one product to delete </strong></h4>
                    </div>
                    <div class="card-body">
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{!! asset('js/dataTables.checkboxes.min.js') !!}"></script>
    <script>
    $('[data-toggle="tooltip"]').tooltip()
    </script>
    <script>
        var productList = '';
        $(document).ready(function() {
            productList = $('#product-list').DataTable({
                'columnDefs': [
                    {
                        'targets': 0,
                        'data': 1,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }
                ],
                'select': {
                    'style': 'multi'
                },
                'rowCallback': function( row, data, index ){
                    // $('.dt-checkboxes').change(function() {
                    //     var checkbox = $(this);
                    //     if(checkbox.prop("checked")){
                    //         $('.btn-del').show();
                    //     }else{
                    //         $('.btn-del').show();
                    //     }
                    // });
                },
                'order': [[1, 'asc']]
            });
        });

        function confirmDelete(product_id) {
            $('#deleteConfirmation').modal();
            $('#product_id').val(product_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#product_id').val('');
        });

         $("#deleteProducts").on("hidden.bs.modal", function () {
            $('#delProdIds').val('');
        });

        $('.btn-delete-products').on('click', function(e){
            var counter = productList.columns().checkboxes.selected()[0].length;

            if(counter > 0){
                $('span.no_products').text(counter);
                $('#deleteProducts').modal();
            }else{
                $('#selectProducts').modal();
            }
        })

        $('.btn-delete').on('click', function(e){
            e.preventDefault();
            
            var ids = '';
            var checkboxes = productList.columns().checkboxes.selected()[0];

            checkboxes.forEach(function(checkbox) {
                ids += checkbox + ',';
            });

            $('#delProdIds').val(ids);
            $('#deleteProductForm').submit();
        })
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
