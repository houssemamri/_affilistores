@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('page_title')
Create Social Campaign
@endsection

@section('content')
@if(in_array('false', $socialCredentials))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <i class="material-icons">close</i>
    </button>
    @foreach($socialCredentials as $key => $credentials)
    @if($credentials == false)
    <span> <b> Please setup {{ ucwords($key) }} API settings </b></span>
    @endif
    @endforeach
    <br>
    <span><b>Go <a href="{{ route('social', Session::get('subdomain')) }}"><u>here</u></a> to setup API for Social Campaigns </b></span>
</div>
@endif

<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Create Social Campaign </strong></h4>
        <p class="card-category">Add new social campaign</p>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills social-campaign-steps">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#step1" id="stepOne">Step 1: Setup schedule and category</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#step2" id="stepTwo">Step 2: Select Social Media Platform</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#step3" id="stepThree">Step 3: Preview</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <form action="{{ route('social.create', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
            {!! csrf_field() !!}
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade show active" id="step1">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="20%"><strong>Automation Name <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter automation name.">info</i></strong></th>
                                    <td>
                                        <input type="text" name="automation_name" value="{{ old('automation_name') }}" placeholder="Enter Automation Name" class="form-control category-name">
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%"><strong>Select Category <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose product category to get your products">info</i></strong></th>
                                    <td>
                                        <select class="form-control" name="category" id="" class="category">
                                            <option>Select Category</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">Enable Autopost <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you want to enable auto posting">info</i></th>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="enable_autopost" checked>
                                                Set to enable
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%"><strong>Schedule Date <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set schedule date to share your products">info</i></strong></th>
                                    <td>
                                        <input type="date" name="schedule_date" min="{{ date('Y-m-d') }}" value="{{ old('schedule_date') }}" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%"><strong>Schedule Time <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set schedule time to share your products">info</i></strong></th>
                                    <td>
                                        <select name="schedule_time" id="" class="form-control">
                                            @for($i = 0; $i <= 24; $i++)
                                            <option value="{{ $i . ':00' }}" {{ (old('schedule_time') == ($i . ':00')) ? 'selected' : '' }}>{{ $i . ':00' }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%"><strong>Products to share <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose which products you want to share. Maximum of 5 products">info</i></strong><span class="text-muted">(Limit 5 products)</span></th>
                                    <td>
                                        <select id="productList" class="form-control">
                                            
                                        </select>
                                        <hr>
                                        <div class="selected">
                                            
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pull-right">
                        <a class="btn btn-primary btn-next"  href="#" data-next="#stepTwo">Next: Step 2</a>
                        <a href="{{ route('social.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
                <div class="tab-pane fade" id="step2">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="30%">Facebook</th>
                                    <td>
                                        @if($socialCredentials['facebook'])
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <!-- <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" value="facebook_timeline" name="type[]">
                                                                Post to Timeline
                                                                <span class="form-check-sign">
                                                                    <span class="check"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </td> -->
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" value="facebook_pages" name="type[]">
                                                                Post to Pages
                                                                <span class="form-check-sign">
                                                                    <span class="check"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" value="facebook_groups" name="type[]">
                                                                Post to Groups
                                                                <span class="form-check-sign">
                                                                    <span class="check"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @else
                                        <a class="btn btn-xs btn-default" href="{{  route('social', ['subdomain' => Session::get('subdomain')]) }}">Add Facebook settings</a>
                                        @endif
                                    </td>
                                </tr>
                                <tr width="30%">
                                    <th>Twitter</th>
                                    <td>
                                        @if($socialCredentials['twitter'])
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" value="twitter" name="type[]">
                                                Twitter Timeline
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        @else
                                        <a class="btn btn-xs btn-default" href="{{  route('social', ['subdomain' => Session::get('subdomain')]) }}">Add Twitter settings</a>
                                        @endif
                                    </td>
                                    
                                </tr>
                                <tr width="30%">
                                    <th>Tumblr</th>
                                    <td>
                                        @if($socialCredentials['tumblr'])
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" value="tumblr" name="type[]">
                                                Tumblr Post
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        @else
                                        <a class="btn btn-xs btn-default" href="{{  route('social', ['subdomain' => Session::get('subdomain')]) }}">Add Tumblr settings</a>
                                        @endif
                                    </td>
                                    
                                </tr>
                                <tr width="30%">
                                    <th>Pinterest</th>
                                    <td>
                                        @if($socialCredentials['pinterest'])
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" value="pinterest" name="type[]">
                                                Pinterst Pin
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        @else
                                        <a class="btn btn-xs btn-default" href="{{  route('social', ['subdomain' => Session::get('subdomain')]) }}">Add Pinterest settings</a>
                                        @endif
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <th width="30%">Instagram</th>
                                    <td>
                                        @if($socialCredentials['instagram'])
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" value="instagram" name="type[]">
                                                Instagram post
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        @else
                                        <a class="btn btn-xs btn-default" href="{{  route('social', ['subdomain' => Session::get('subdomain')]) }}">Add Instagram settings</a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pull-right">
                        <a class="btn btn-primary btn-next" href="#" data-next="#stepThree">Next: Step 3</a>
                        <a href="{{ route('social.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
                <div class="tab-pane fade" id="step3">
                    <div class="row">
                        <div class="col-lg-4">
                            @if($socialCredentials['facebook'])
                            <div class="card">
                                <div class="card-body preview-wrapper">
                                    <div class="tweet-wrapper">
                                        <div class="row vertical-center">
                                            <div class="col-lg-12">
                                                <div class="fb-avatar">
                                                    <img src="{{ json_decode($facebook)->avatar }}" alt="" class="img-fluid" width="60px">
                                                    <h3 class="m-0">  
                                                        <strong>{{ json_decode($facebook)->name }}</strong>  <span class="text-muted small">Shared a <a href="{{ route('index.product.show', ['subdomain' => Session::get('subdomain'), 'permalink' => $sampleProduct->permalink]) }}">link</a></span>
                                                    </h3>
                                                    <p>Published by {{ json_decode($facebook)->name }} 1 min</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="tweet-content text-center">
                                                    <div class="tweet-img">
                                                        <img src="{{ $sampleProduct->image }}" class="w-100 img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body facebook-footer preview-footer bg-light">
                                    <p class="text-uppercase text-muted m-0">{{ str_replace('https://', '', route('index', Session::get('subdomain'))) }}</p>
                                    <h4 class="m-0"><b>{{ $sampleProduct->name }}</b></h4>
                                    <p class="text-muted preview-description m-0">{{ strip_tags($sampleProduct->description) }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            @if($socialCredentials['twitter'])
                                <div class="card">
                                    <div class="card-body preview-wrapper">
                                        <div class="tweet-wrapper">
                                            <div class="row vertical-center">
                                                <div class="col-lg-12">
                                                    <div class="tweet-avatar">
                                                        <h3>  
                                                            <img src="{{ json_decode($twitter)->avatar }}" alt="" class="img-fluid" width="60px">
                                                            <strong>{{ json_decode($twitter)->name }}</strong>  <span class="text-muted small">{{ '@' . json_decode($twitter)->screen_name }}</span>
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="tweet-content text-center">
                                                        <div class="tweet-img">
                                                            <img src="{{ $sampleProduct->image }}" class="w-100 img-fluid" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body twitter-footer preview-footer">
                                        <h4 class="m-0"><b>{{ $sampleProduct->name }}</b></h4>
                                        <h5 class=" preview-description m-0">{{ strip_tags($sampleProduct->description) }}</h5>
                                        <p class=" text-muted m-0">{{ str_replace('https://', '', route('index', Session::get('subdomain'))) }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            @if($socialCredentials['instagram'])
                            @endif
                        </div>
                    </div>
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('social.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </div>
           
            
            <div class="clearfix"></div>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip()
        var limitcounter = 1;

        function remove(element){
            var input = $(element).parent().find('input');
            $('#productList').append('<option value='+input.val()+'>'+input.attr('data-name')+'</option>');
            $(element).parent().remove();
            limitcounter--;
        }

        $('.btn-next').on('click', function() {
            var next = $(this).attr('data-next');
            
            $(next).trigger('click');
        })

        $('select[name=category]').change(function(){
            limitcounter = 1;
            $('div.selected').empty();
            var URL = "{{ route('get.products', ['subdomain' => Session::get('subdomain'), 'category' => 'category_id']) }}";
            URL = URL.replace('category_id', $(this).find(':selected').val());

            axios.get(URL)
            .then(function(response){
                var products = response.data.data;
                var options = '<option value="">Please select product to share</option>';
                var productList = $('#productList');
                
                $.each(products, function(index, product){
                    options += '<option value='+product.id+'>'+product.name+'</option>';
                });

                productList.empty();
                productList.append(options);
            })
            .catch(function(error){
                console.log(error);
            })
        })

        $('#productList').change(function(){
            var product = $(this).find(':selected');
            var span = '';
            if(limitcounter <= 5 && product.val()){
                span += '<span>';
                span += '    <input type="hidden" name="products[]" value="'+product.val()+'" data-name="'+product.text()+'">';
                span += '    <span class="badge badge-primary product-badge">'+product.text()+'</span>';
                span += '    <span class="badge badge-default product-remove" onclick="remove(this);">X</span>';
                span += '</span>';

                $('div.selected').append(span);
                limitcounter++;
                $(product).remove();
            }
            
        });
    </script>
    <script>
        $('div.selected').bind('DOMSubtreeModified', function(){
            if($('input[name="products[]"]').length == 1){
                var PROD_URL = "{{ route('get.product', ['subdomain' => Session::get('subdomain'), '' => 'prod_id']) }}";
                PROD_URL = PROD_URL.replace('prod_id', $($('input[name="products[]"]')[0]).val());
                var productURL = "{{ route('index', Session::get('subdomain')) }}"

                axios.get(PROD_URL)
                    .then(function(response){
                        var product = response.data.data;
                        productURL = productURL.replace('https://', '');
                        productDescription = product.description.replace(/(<([^>]+)>)/ig,"");

                        if($('.twitter-footer')){
                            var tweet = "";

                            tweet += '<h4><b>' + product.name +  '</b></h4>'
                            tweet += '<h5 class=" preview-description m-0">' + productDescription + '</h5>'
                            tweet += '<p class=" text-muted m-0">' + productURL + '</p>'

                            $('.twitter-footer').empty();
                            $('.twitter-footer').append(tweet);
                        }

                        if($('.facebook-footer')){
                            var facebook = "";

                            facebook += '<p class="text-uppercase text-muted m-0">' + productURL + '</p>'
                            facebook += '<h4 class="m-0"><b>' + product.name + '</b></h4>'
                            facebook += '<p class="text-muted preview-description m-0">' + productDescription + '</p>'

                            $('.facebook-footer').empty();
                            $('.facebook-footer').append(facebook);
                        }

                        $('img.w-100').each(function(){
                            $(this).attr('src', product.image)
                        });

                        console.log(product)
                    })
                    .catch(function(error){
                        console.log(error);
                    })
            }
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
