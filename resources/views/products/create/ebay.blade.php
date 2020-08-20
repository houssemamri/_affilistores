@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('page_title')
Ebay Product
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Add A New Ebay Product </strong></h4>
        <p class="card-category">Add new products to your store</p>
    </div>
    <div class="card-body">
        <h3>Search Products</h3>
        <table class="table">
            <tbody>
                <tr>
                    <th width="20%"><strong>Category <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose a category to search">info</i></strong></th>
                    <td>
                        <select name="searchIndex" id="searchIndex" class="form-control">
                            @foreach($searchIndices as $key => $searchIndex)
                                <option value="{{ $searchIndex }}">{!! $key !!}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="20%"><strong>Keyword <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter a keyword of the product to search">info</i></strong></th>
                    <td>
                        <input type="text" id="keyword" placeholder="Enter Keyword" class="form-control" required>
                    </td>
                </tr>
                <tr>
                    <th width="20%"></th>
                    <td>
                        <button type="button" class="btn btn-primary search"> Search </button>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="container">
    <div class="progress loading">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
        <small class="justify-content-center d-flex">Fetching products. Please wait</small>
        </div>
    </div>
</div>
<form action="{{ route('products.create', ['source' => $source,  'subdomain' => Session::get('subdomain')]) }}" method="post" id="product-form">
    {{ csrf_field() }}
    <div class="card results">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table member-table text-center" id="product-list">
                    <thead class="">
                        <tr>
                            <th></th>
                            <th>Product Name</th>
                            <th>Image</th>
                            <th>Product Id</th>
                            <th>Price</th>
                            <th>View Details</th>
                        </tr>
                    </thead>
                    <tbody class="products">
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card category-tag-form">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> Next: Category and Tag </strong></h4>
            <p class="card-category">Set Categories and Tags</p>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="col-lg-12">
                    <ul class="option-list">
                        
                    </ul>
                </div>
                <label class="bmd-label-floating">Category <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set categories of the product. Can select mutiple categories">info</i></label>
                <select id="category" class="form-control">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="bmd-label-floating">Tags <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your product tags">info</i></label>
                <input type="text" name="tags" placeholder="Enter Tags" value="{{ $tags }}">

                <button type="button" class="btn btn-sm btn-danger remove-all-tags">Remove all tags</button>
            </div>

            <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" name="auto_approve" type="checkbox">
                    Auto Approve Customer Reviews 
                    <span class="form-check-sign">
                        <span class="check"></span>
                    </span>
                </label>
                <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check to auto approve customer reviews. Uncheck to manually approve customer reviews">info</i>
            </div>

            <div class="form-group">
                <label class="bmd-label-floating"> Choose publish date </label>
            </div>
            
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="publish" id="now" value="now" checked>
                    Now <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Published products today.">info</i>
                    <span class="circle">
                        <span class="check"></span>
                    </span>
                </label>
            </div>

            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="publish" id="scheduled" value="scheduled ">
                    Scheduled <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose a schedule date to publish your products">info</i>
                    <span class="circle"> 
                        <span class="check"></span>
                    </span>
                    <input type="date" name="published_date" min="{{ date('Y-m-d') }}" placeholder="Enter Category Name" class="form-control published-date">
                </label>
            </div>
            
            <input type="hidden" name="productsSelected" id="productsSelected">

            <button class="btn btn-primary btn-publish" type="submit"> <i class="material-icons publish-loading">autorenew</i> Publish</button>
        </div>
    </div>
</form>

@endsection

@section('custom-scripts')
    <script src="{!! asset('js/jquery.dataTables.min.js') !!}"></script>
    <script src="{!! asset('js/dataTables.bootstrap4.min.js') !!}"></script>
    <script src="{!! asset('js/axios.min.js') !!}"></script>
    <script src="{!! asset('js/dataTables.checkboxes.min.js') !!}"></script>
    <script src="{!! asset('js/tagify.min.js') !!}"></script>
    
    <script>
        function remove(element){
            var option = $(element);
            var optionHtml = '<option value="'+$(element).parent().next('input').val()+'">'+$(element).parent().attr('data-name')+'</option>';

            $('select#category').append(optionHtml);
            option.parent().parent().remove();
        }
        
        function clearAll(){
            $('.loading').show();
            $('.search').attr('disabled', '');
            $('.results').hide();
            $('.category-tag-form').hide();
            $('.published-date').val('');

            if($('.option-list > li').length > 0){
                var options = $('.option-list').find('li');
                $.each(options, function(){
                    $(this).find('span.remove-option').trigger( "click" );
                });
            }

            var table = $('#product-list').DataTable();
            table.destroy();
        }

        function existing(id){
            var products = JSON.parse('{!! $products_reference !!}');
            var found = $.inArray(id, products);

            if(found > -1){
               return true;
            }

            return false;
        }
        
        function createPermalink(permalink){
            permalink = permalink.split(' ').join('-');
            permalink = permalink.replace(/[^a-z0-9\s]/gi, '-');
            permalink = permalink.split('_').join('-');
            permalink = permalink.replace(/-{2,}/g,'-');
            permalink = permalink.toLowerCase().trim();

            return permalink;
        }

        function alertError(msg){
            $.notify({
                icon: "error",
                message: msg,
            },{
                type: 'danger'
            });
        }

        $('[data-toggle="tooltip"]').tooltip()
        $('[name=tags]').tagify();
        $('[name=tags]').tagify({duplicates : false});

        $('.remove-all-tags').on('click', function(){
            $('tag').remove();
        });

        var productList = '';

        $('.search').on('click', function(){
            var searchIndex = $('#searchIndex').val();
            var keyword = $('#keyword').val();
            var pageLength = searchIndex == 'All' ? ['1', '2', '3', '4', '5'] : ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
            var html = '';
            var products = [];

            clearAll();
            
            axios.post("{{ route('ebay.search', ['subdomain' => Session::get('subdomain')]) }}", {
                searchIndex: searchIndex,
                keyword: keyword,
            })
            .then(function (responses) {
                if(responses.data.error){
                    $('.loading').hide();
                    alertError(responses.data.error);
                }else if(responses.data.length == 0){
                    alertError('No results found.');
                }
                console.log(responses.data)

                html += '<thead>';
                html += '   <tr>';
                html += '       <th></th>';
                html += '       <th>Product Id</th>';
                html += '       <th>Product Name</th>';
                html += '       <th>Image</th>';
                html += '       <th>Price</th>';
                html += '       <th>View Details</th>';
                html += '   </tr>';
                html += '</thead>';
                html += '<tbody>';

                $.each(responses.data, function(i, product){
                        console.log(product);
                        // var image = (product.galleryURL) ? product.galleryURL[0] : 'http://thumbs1.ebaystatic.com/pict/04040.jpg';
                        var image = (product.galleryURL) ? product.galleryURL[0] : '';
                        
                        if(!image || image == 'http://thumbs1.ebaystatic.com/pict/04040_0.jpg') return;

                        var data = {
                            title: escape(product.title[0]),
                            image: escape(image.replace('http:', 'https:')),
                            product_id: product.itemId[0],
                            currency: (product.sellingStatus[0].convertedCurrentPrice[0]) ? product.sellingStatus[0].convertedCurrentPrice[0]['@currencyId'] : 'USD',
                            price: (product.sellingStatus[0].convertedCurrentPrice[0]) ? product.sellingStatus[0].convertedCurrentPrice[0].__value__ : 0.00,
                            details_link: escape(product.viewItemURL[0].replace('http', 'https')),
                            description: '',
                            customer_review_link: '',
                            images: [],
                            permalink: createPermalink(product.title[0]),
                        };

                        console.log(data);
                        data = JSON.stringify(data);
                        
                        html += '<tr>';
                        html += '   <td></td>';
                        html += '   <td class="item-id">'+product.itemId+'</td>';
                        html += "   <td><input class='product-data' type='hidden' value='"+data+"'>"+product.title+"</td>";
                        html += '   <td><img src="'+ image.replace('http:', 'https:') +'" alt="'+product.title+'" class="img-fluid"></td>';
                        html += '   <td>USD '+product.sellingStatus[0].convertedCurrentPrice[0].__value__+'</td>';
                        html += '   <td><a href="'+ product.viewItemURL[0].replace('http', 'https') +'" target="_blank">View Details</a></td>';
                        html += '</tr>';
                        
                        // $('tbody.products').append(html);
                });

                html += '</tbody>';

                $('#product-list').html(html);

                $('.loading').hide();
                $('.results').show();
                $('.category-tag-form').show();
              
                productList = $('#product-list').DataTable({
                    'pageLength': 50,
                    'columnDefs': [
                        {
                            'targets': 0,
                            'checkboxes': {
                                'selectRow': true
                            }
                        }
                    ],
                    'select': {
                        'style': 'multi'
                    },
                    'rowCallback': function( row, data, index ){
                        if(existing(data[1])){
                            $('td:eq(0)', row).html('');
                            $(row).addClass('no-select');
                        } else {
                            $(row).removeClass('no-select');
                        }

                        // $('.dt-checkboxes').change(function() {
                        //     var checkbox = $(this);
                        //     if(checkbox.prop("checked")){
                        //         checkbox.parent().next('td').find('input').attr('name', 'products[]');
                        //     }else{
                        //         checkbox.parent().next('td').find('input').removeAttr('name');
                        //     }
                        // });
                    },
                    // 'order': [[1, 'asc']]
                });
                
                $('.dt-checkboxes-cell > input[type=checkbox]').change(function(){
                    $('.dt-checkboxes').trigger('change');
                })

                $('.search').removeAttr('disabled');
            }).catch(function (error) {
                $('.loading').hide();
                $('.search').removeAttr('disabled');
                alertError('Oops! Something went wrong. Please try again.');
                console.log(error);
            });
            
        });

        $('select#category').change(function(){
            var option = $(this).find(':selected');

            var optionHtml = '<li>';
            optionHtml += '<span class="badge badge-warning" data-name="'+option.text()+'">'+option.text()+' <span class="remove-option" onclick="remove(this);">X</span>  </span>';
            optionHtml += '<input type="hidden" name="category[]" value="'+option.val()+'">';
            optionHtml += '</li>';
            
            $('.option-list').append(optionHtml);
            option.remove();
        });

        $('input[type="radio"]').change(function(){
            if($(this).attr('id') == 'now')
                $('.published-date').hide();
            else    
                $('.published-date').show();
        });

        $('.btn-publish').on('click', function(event){
            event.preventDefault();

            // var product_count = $('input[name="products[]"]').length;
            var category_count = $('input[name="category[]"]').length;
            var product_count = productList.columns().checkboxes.selected()[0].length;
            
            if(product_count == 0){
                alertError('Please select atleast one product');
            }
            
            if(category_count == 0){
                alertError('Please select atleast one category');
            }
            
            if(category_count > 0 && product_count > 0){
                var pTable = $("#product-list").dataTable();
                var selected = [];

                $("input:checkbox", pTable.fnGetNodes()).each(function () {
                    var checkbox = $(this)

                    if(checkbox.prop("checked")){
                        selected.push(checkbox.parents('tr').find('input.product-data').val());
                    }
                })
                
                if($(this).prop('disabled') == false){
                    $('.publish-loading').attr('style', 'display: inline-block !important');
                    $('#productsSelected').val(JSON.stringify(selected));
                    $("form#product-form").submit();
                }
                
                $(this).prop('disabled', true);
            }
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
