@extends('master')

@section('page_title')
Theme Settings
@endsection

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{!! asset('css/image-picker.css') !!}" />
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Theme Settings </strong></h4>
        <p class="card-category">Give an elegant look and appeal to your store</p>
    </div>
    <div class="card-body">
        <form action="{{  route('theme', ['subdomain' => Session::get('subdomain')]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Theme <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Select the best theme for your store.">info</i></label>
                                <select class="form-control" name="theme" id="country">
                                    @foreach($themes as $theme)
                                        <option value="{{ $theme->theme->id }}" {{ ($store->storeTheme->theme_id == $theme->theme->id) ? 'selected' : '' }}>{{ $theme->theme->name }}</option>
                                    @endforeach
                                </select>   
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Color Schemes <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Select the best color scheme for your store.">info</i></label>
                                <select class="form-control" name="color_scheme" id="country">
                                    @foreach($selectedColorSchemes as $selectedColorScheme)
                                        <option value="{{ $selectedColorScheme->id }}" {{ ($store->storeTheme->color_scheme_id == $selectedColorScheme->id) ? 'selected' : '' }}>{{ $selectedColorScheme->name }}</option>
                                    @endforeach
                                </select>   
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Logo <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Upload logo of your store. Recommended size 400 x 400 pixels.">info</i></label><br>

                                <img src="{{ asset('img/uploads/'.$store->subdomain.'/logo/' . $store->logo) }}" alt="" class="img-fluid" id="logo-img">
                                
                                <input type="file" name="logo" accept="image/*" class="form-control-file" id="logo" >
                                <div class="btn-group">
                                    <label for="logo" class="btn btn-block btn-info btn-choose-file">Choose File</label>
                                    <a href="#" class="btn btn-info btn-pixabay-logo">Choose from Pixabay</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Favicon <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Upload favicon of your store. Recommended size 32 x 32 pixels.">info</i></label><br>

                                <img src="{{ asset('img/uploads/'.$store->subdomain.'/logo/' . $storeTheme->favicon) }}" alt="" class="img-fluid" id="favicon-img">

                                <input type="file" name="favicon" accept="image/*" class="form-control-file" id="favicon" >
                                <div class="btn-group">
                                    <label for="favicon" class="btn btn-block btn-info btn-choose-file">Choose File</label>
                                    <a href="#" class="btn btn-info btn-pixabay-favicon">Choose from Pixabay</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 pixabay">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Pixabay </strong></h4>
                            <p class="card-category">Search free images in Pixabay</p>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Search </td>
                                        <td>
                                            <input type="text" class="form-control" id="query">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Image Type </td>
                                        <td>
                                            <select id="image_type" class="form-control">
                                                <option value="all">All</option>
                                                <option value="images">Images</option>
                                                <option value="photo">Photo</option>
                                                <option value="illustration">Illustration</option>
                                                <option value="vector">Vector</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Category </td>
                                        <td>
                                            <select id="category" class="form-control">
                                            @foreach($pixabayCategories as $category)
                                            <option value="{{ $category }}">{{ ucwords($category) }}</option>
                                            @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Order </td>
                                        <td>
                                            <select id="order" class="form-control">
                                                <option value="popular">Popular</option>
                                                <option value="latest">Latest</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#" class="btn btn-primary pixabaySearch">Search</a>    
                                        </td>
                                        <td>
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body pixabay-results">

                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="#" class="btn btn-warning btn-preview">Preview</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{!! asset('js/axios.min.js') !!}"></script>
    <script src="{!! asset('js/image-picker.js') !!}"></script>

    <script>
        var schemes = {!! $colorSchemes !!}
        var type = '';

        function readURL(input, img) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $(img).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#logo").change(function(){
            readURL(this, '#logo-img');
        });

        $("#favicon").change(function(){
            readURL(this, '#favicon-img');
        });

        $('select[name=theme]').change(function(){
            var theme = $(this);
            var schemeDiv = $('select[name=color_scheme]');
            var options = '';

            $.each(schemes, function(index, scheme){
                if(scheme.theme_id == theme.val()){
                    options += '<option value="'+ scheme.id +'">'+ scheme.name +'</option>';
                }
            })

            schemeDiv.empty();
            schemeDiv.append(options);
        })

        $('.btn-pixabay-favicon, .btn-pixabay-logo').on('click', function(){
            if($(this).hasClass('btn-pixabay-logo')){
                type = 'pixabayLogo';
            }

            if($(this).hasClass('btn-pixabay-favicon')){
                type = 'pixabayFavicon';
            }

            $('.pixabay-results').empty();
            $('.pixabay').show();
        })
        
        $('.btn-choose-file').on('click', function(){
            $('.pixabay').hide();
        })

        $('.btn-preview').on('click', function(){
            var url = '{!! route("index", [Session::get("subdomain"), "preview" => "true", "theme" =>  "theme_id", "scheme" =>  "color_scheme_id"]) !!}';
            url = url.replace('theme_id', $('select[name=theme]').val())
            url = url.replace('color_scheme_id',  $('select[name=color_scheme]').val())
            
            window.open(url, '_blank');
        })

        $('.pixabaySearch').on('click', function(){
            var pixabay_key = '{{ $site["pixabay"] }}';
            var html = '';

            var params = { 
                key: pixabay_key, 
                q: $('#query').val(), 
                image_type: $('#image_type').val(),
                category: $('#category').val(),
                order: $('#order').val(),
                per_page: 50
            };
            
            var parameter = $.param(params);

            axios.get('https://pixabay.com/api/?' + parameter)
            .then(function(response){
                console.log(response)
                $('.pixabay-results').empty();
                var images = response.data.hits;

                if(response.data.totalHits == 0 || response.data.total == 0){
                    $.notify({
                    icon: "error",
                        message: 'No image found. Try different search combinations',
                    },{
                        type: 'danger'
                    });
                }else{

                    html += '<h4>Select image to use</h4>';
                    html += '<select name="'+type+'" id="pixabay-images">';
                    $.each(images, function(i, image){
                        html += '<option data-img-src="'+ image.previewURL +'" value="'+ image.webformatURL +'">';
                        html += '</option>';
                    });

                    html += '</select>';

                    $('.pixabay-results').append(html);
                    $("#pixabay-images").imagepicker();
                }
            })
            .catch(function(error){
                $.notify({
                    icon: "error",
                    message: error.msg,
                },{
                    type: 'danger'
                });
            })

        });

        $('[data-toggle="tooltip"]').tooltip()
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
