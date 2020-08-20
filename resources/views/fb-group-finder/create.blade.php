@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('page_title')
Amazon Product
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Add A New Amazon Product </strong></h4>
        <p class="card-category">Add new products to your store</p>
    </div>
    <div class="card-body">
        <h3>Search Products</h3>
        <table class="table">
            <tbody>
                <tr>
                    <th width="20%"><strong>Category</strong></th>
                    <td>
                        <select name="searchIndex" id="searchIndex" class="form-control">
                            @foreach($searchIndices as $searchIndex)
                                <option value="{{ $searchIndex }}">{{ $searchIndex }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="20%"><strong>Keyword</strong></th>
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
                <label class="bmd-label-floating">Category</label>
                <select id="category" class="form-control">
                    <option value=""></option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="bmd-label-floating">Tags</label>
                <input type="text" name="tags" placeholder="Enter Tags" >
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="publish" id="now" value="now" checked>
                    Now
                    <span class="circle">
                        <span class="check"></span>
                    </span>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="publish" id="scheduled" value="scheduled ">
                    Scheduled
                    <span class="circle">
                        <span class="check"></span>
                    </span>
                    <input type="date" name="published_date" min="{{ date('Y-m-d') }}" placeholder="Enter Category Name" class="form-control published-date">
                </label>
            </div>
            <button class="btn btn-primary btn-publish" type="submit">Publish</button>
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
        function getProducts(searchIndex, keyword, pageLength){
            var products = [];
            for(var i = 1; i <= pageLength; i++){
                axios.post("{{ route('amazon.search', ['subdomain' => Session::get('subdomain')]) }}", {
                    searchIndex: searchIndex,
                    keyword: keyword,
                    itemPage: i,
                })
                .then(function (response) {
                    products.push(response.data.Items.Item);
                    console.log(response.data)
                })
                .catch(function (error) {
                    loading.hide();
                    console.log(error);
                });
            }

            return products;
        }

        function getImage(imageSets){
            if(imageSets[0]){
                var imageSet = [];
                $.each(imageSets, function(index, image){
                    if(image['@attributes'].Category == 'primary'){
                        imageSet = image;
                    }
                });

                if(imageSet.length == 0)
                    return imageSets[0].MediumImage.URL;
                else
                    return imageSet.MediumImage.URL;

            }else{
                return imageSets.MediumImage.URL;
            }
        }

        function getImages(imageSets){
            var images = [];

            if(imageSets[0]){
                $.each(imageSets, function(index, image){
                    if(image['@attributes'].Category == 'variant'){
                        images.push(image.LargeImage.URL);
                    }
                });
            }

            return images;
        }

        function remove(element){
            var option = $(element);
            var optionHtml = '<option value="'+$(element).parent().next('input').val()+'">'+$(element).parent().attr('data-name')+'</option>';

            $('select#category').append(optionHtml);
            option.parent().parent().remove();
        }
        
        function clearAll(){
            $('.loading').show();
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

        $('[name=tags]').tagify();
        $('[name=tags]').tagify({duplicates : false});

        $('.search').on('click', function(){
            var searchIndex = $('#searchIndex').val();
            var keyword = $('#keyword').val();
            var pageLength = searchIndex == 'All' ? ['1', '2', '3', '4', '5'] : ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
            var html = '';
            var products = [];

            clearAll();
            
            axios.post("{{ route('amazon.search', ['subdomain' => Session::get('subdomain')]) }}", {
                searchIndex: searchIndex,
                keyword: keyword,
                pageLength: pageLength,
            })
            .then(function (responses) {
                console.log(responses.data)

                html += '<thead>';
                html += '   <tr>';
                html += '       <th></th>';
                html += '       <th>Product Name</th>';
                html += '       <th>Image</th>';
                html += '       <th>Product Id</th>';
                html += '       <th>Price</th>';
                html += '       <th>View Details</th>';
                html += '   </tr>';
                html += '</thead>';
                html += '<tbody>';

                $.each(responses.data, function(i, response){
                    var products = response.Items.Item;
                    $.each(products, function(index, product){
                        var image = (product.ImageSets) ? getImage(product.ImageSets.ImageSet) : '{{ asset("img/amazon-no-image.jpg") }}' ;
                        var price = (product.ItemAttributes.ListPrice) ? product.ItemAttributes.ListPrice.FormattedPrice : '<a href="'+product.DetailPageURL+'">Check Price</a>'
                        var data = {
                            title: product.ItemAttributes.Title,
                            image: image,
                            product_id: product.ASIN,
                            price: (product.ItemAttributes.ListPrice) ? price : 0.00,
                            details_link: escape(product.DetailPageURL),
                            description: (product.ItemAttributes.Feature) ? escape(product.ItemAttributes.Feature.toString())  : '',
                            customer_review_link: (product.CustomerReviews.HasReviews) ? escape(product.CustomerReviews.IFrameURL) : '',
                            images: (product.ImageSets) ? getImages(product.ImageSets.ImageSet) : [],
                            permalink: createPermalink(product.ItemAttributes.Title),
                        };

                        console.log(data);
                        data = JSON.stringify(data);
                        
                        html += '<tr>';
                        html += '   <td></td>';
                        html += "   <td><input class='product-data' type='hidden' value='"+data+"'>"+product.ItemAttributes.Title+"</td>";
                        html += '   <td><img src="'+image+'" alt="'+product.ItemAttributes.Title+'" class="img-fluid"></td>';
                        html += '   <td>'+product.ASIN+'</td>';
                        html += '   <td>'+price+'</td>';
                        html += '   <td><a href="'+product.DetailPageURL+'" target="_blank">View Details</a></td>';
                        html += '</tr>';
                        
                        // $('tbody.products').append(html);
                    });
                });
                html += '</tbody>';

                $('#product-list').html(html);

                $('.loading').hide();
                $('.results').show();
                $('.category-tag-form').show();
              
                $('#product-list').DataTable({
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
                        if(existing(data[3])){
                            $('td:eq(0)', row).html('');
                            $(row).addClass('no-select');
                        } else {
                            $(row).removeClass('no-select');
                        }
                    },
                    'order': [[1, 'asc']]
                });

                $('.dt-checkboxes').change(function() {
                    var checkbox = $(this);
                    if(checkbox.prop("checked")){
                        checkbox.parent().next('td').find('input').attr('name', 'products[]');
                    }else{
                        checkbox.parent().next('td').find('input').removeAttr('name');
                        }
                });

            }).catch(function (error) {
                $('.loading').hide();
                $.notify({
                    icon: "error",
                    message: 'Something went wrong. Please try again',
                },{
                    type: 'danger'
                });
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

            var product_count = $('input[name="products[]"]').length;
            var category_count = $('input[name="category[]"]').length;
            
            if(product_count == 0){
                $.notify({
                    icon: "error",
                    message: 'Please select atleast one product',
                },{
                    type: 'danger'
                });
            }
            
            if(category_count == 0){
                $.notify({
                    icon: "error",
                    message: 'Please select atleast one category',
                },{
                    type: 'danger'
                });
            }
            
            if(category_count > 0 && product_count > 0){
                $("form#product-form").submit();
            }
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
