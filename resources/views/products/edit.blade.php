@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('page_title')
Edit Product
@endsection

@section('content')
<form action="{{ route('products.edit', ['subdomain' => Session::get('subdomain'), 'id' => $id]) }}" method="POST" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> Edit your product </strong></h4>
            <p class="card-category">Update product details</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Name <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your product's name">info</i></label>
                        <input type="text" name="name" value="{{ $product->name }}" class="form-control">
                    </div>
                </div>

                 <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Product ID <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your product ID">info</i></label>
                        <input type="text" name="product_id" value="{{ $product->reference_id }}" class="form-control">
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Permalink <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set the link of your product">info</i></label>
                        <br><span>{{ URL::to('/') }}/product/<span class="permalink">{{ $product->permalink }}</span></span> 
                        <input type="text" name="permalink" class="form-control product-permalink" value="{{ $product->permalink }}">
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="bmd-label-floating">Currency <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Select product price currency">info</i></label>
                                <select name="currency" class="form-control">
                                @foreach($currencies as $key => $currency)
                                <option value="{{ $key }}" {{ $key == $product->currency ? 'selected' : ''}}>{{ $key }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <label class="bmd-label-floating">Price <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your product's price">info</i></label>
                                <input type="number" step="any" name="price" value="{{ $product->price }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Affiliate Link <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your product's affiliate link">info</i></label>
                        <input type="text" name="affiliate_link" value="{{ $product->details_link }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" name="auto_approve" type="checkbox" {{ $product->auto_approve == 1 ? 'checked' : ''}}>
                            Auto Approve Customer Reviews 
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                        <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check to auto approve customer reviews. Uncheck to manually approve customer reviews">info</i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" name="show_related_tweets" type="checkbox" {{ $product->show_tweets == 1 ? 'checked' : ''}}>
                            Show Related Tweets
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                        <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check to show related Tweets">info</i>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Description <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your product's description ">info</i></label>
                        <textarea class="form-control editor" name="description" id="editor-body">{!! $product->description !!}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-primary">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs" data-tabs="tabs">
                        <li class="nav-item">
                            <a class="nav-link active show" href="#category" data-toggle="tab">
                                Category <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Update your product's categories. Can choose multiple">info</i>
                                <div class="ripple-container"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#seosettings" data-toggle="tab">
                                SEO Settings <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set search enginge optimization of your product to increase search ranking">info</i>
                                <div class="ripple-container"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tags" data-toggle="tab">
                                Tags <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Update your product's tag">info</i>
                                <div class="ripple-container"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#images" data-toggle="tab">
                                Images <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Upload additional images of your product">info</i>
                                <div class="ripple-container"></div>
                            </a>
                        </li>
                        @if(in_array('youtube videos', $features))
                        <li class="nav-item">
                            <a class="nav-link" href="#youtube" data-toggle="tab">
                                YouTube <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add / Update YouTube links related to your product">info</i>
                                <div class="ripple-container"></div>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="#blog" data-toggle="tab">
                                Product Blog <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add / Update Blog for your product">info</i>
                                <div class="ripple-container"></div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade show active" id="category">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="option-list">
                                @foreach($product->categories as $category)
                                @if(isset($category->category->name))
                                <li><span class="badge badge-warning" data-name="{{ $category->category->name }}"> {{ $category->category->name }} <span class="remove-option" onclick="remove(this);">X</span>  </span><input type="hidden" name="category[]" value="{{ $category->category_id }}"></li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Category</label>
                                <select id="category" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        @if($product->categories->where('category_id', $category->id)->count() == 0)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="seosettings">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th width="20%"><strong>Meta Title <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add Meta Title of your product">info</i></strong></th>
                                        <td>
                                            <input type="text" name="meta_title" placeholder="Enter Meta Title" class="form-control" value="{{ $product->seoSettings->meta_title }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="20%"><strong>Meta Description <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add Meta Description of your product">info</i></strong></th>
                                        <td>
                                            <textarea name="meta_description" class="form-control"  cols="1" rows="5">{!! $product->seoSettings->meta_description !!}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="20%"><strong>Meta Keywords <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add Meta Keywords of your product. Max 8 keywords can be added">info</i></strong></th>
                                        <td>
                                            <input type="text" name="meta_keywords" placeholder="Enter Meta Keywords" value="{{ $product->seoSettings->meta_keywords }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th withd="20%">Robots Meta NoIndex <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you don't want your product be rank in search engines">info</i></th>
                                        <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="robots_meta_no_index" {{ ($product->seoSettings->robots_meta_no_index == 1) ? 'checked' : '' }}>
                                                Set to active
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th withd="20%">Robots Meta NoFollow <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you don't want your product be rank in search engines">info</i></th>
                                        <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="robots_meta_no_follow" {{ ($product->seoSettings->robots_meta_no_follow == 1) ? 'checked' : '' }}>
                                                Set to active
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <th withd="20%">Use this SEO settings</th>
                                        <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="status" {{ ($product->seoSettings->status == 1) ? 'checked' : '' }}>
                                                Set to active
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tags">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Tags</label>
                                <input type="text" name="tags" placeholder="Enter Tags" value="{{ $tags }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="images">
                    <div class="row">
                        <div class="col-lg-6">
                            <table class="table table-fixed">
                                <tbody>
                                    <tr>
                                        <th></th>
                                        <th>
                                            @if(isset($product->image))
                                            <div class="form-group">
                                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid w-50">
                                            </div>
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <th >Change Default Image</th>
                                        <th>
                                            <label for="default_image_link">Upload Default Image</label>
                                            <input type="file" name="default_image" accept="image/*" class="form-control-file" id="custom_image"> 
                                            <div class="form-group">
                                            <label for="default_image_link">Enter Default Image Link</label>
                                                <input type="text" placeholder="Image Link" name="default_image_link" class="form-control">
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th >Upload Additional Images</th>
                                        <th><input type="file" name="custom_image[]" accept="image/*" class="form-control-file" id="custom_image" multiple></th>
                                    </tr>
                                <tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        @foreach($product->images as $image)
                        <div class="col-lg-2 product-img-wrapper">
                            <img src="{{ $image->image }}" alt="" class="img-fluid">
                            @if($image->type == 'custom')
                                <button type="button" data-id="{{ Crypt::encrypt($image->id) }}" href="#" class="btn btn-sm btn-danger btn-delete-img">Remove</button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @if(in_array('youtube videos', $features))
                <div class="tab-pane fade" id="youtube">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3><strong>YouTube Videos</strong></h3>
                            <table class="table table-fixed">
                                <tbody>
                                    @foreach($product->videos as $video)
                                    <tr>
                                        <th width="10%">Video Link {{ $loop->iteration }}</th>
                                        <th>
                                            <input type="hidden" name="video_id[]" value="{{ $video->id }}">
                                            <input type="text" name="video_link[]" value="{{ $video->video }}" placeholder="Enter YouTube Link" class="form-control">
                                        </th>
                                        <th>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="video_status[]" {{ ($video->status == 1) ? 'checked' : '' }}>
                                                    Check to enable this video
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </th>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                <div class="tab-pane fade" id="blog">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3><strong>Product Blog</strong></h3>
                            <table class="table table-fixed">
                                <tbody> 
                                    <tr>
                                        <th width="10%">Choose Blog Type</th>
                                        <th>
                                            <select name="blog_type" class="form-control">
                                                <option value='manual_blog'>Manual Blog</option>
                                                <option value='get_blog'>Get from your Blogs</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr class="display-hidden">
                                        <th width="10%">Choose Blog to use</th>
                                        <th>
                                            <select name="blog_id" class="form-control">
                                                @foreach($blogs as $blog)
                                                <option value="{{ $blog->id }}">{{ $blog->title }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                    </tr>
                                    <tr class="manual_blog">
                                        <th width="10%">Blog Title</th>
                                        <th>
                                            <input type="text" name="blog_title" class="form-control" value="{{ (isset($product->blog)) ? $product->blog->title : '' }}">
                                        </th>
                                    </tr>
                                    <tr class="manual_blog">
                                        <th width="10%">Blog Description</th>
                                        <th>
                                            <textarea name="blog_description" id="editor-blog-description" rows="5" class="form-control editor">
                                                {!! (isset($product->blog)) ? $product->blog->description : '' !!}
                                            </textarea>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th width="10%">Publish</th>
                                        <th>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="blog_published" {{ (isset($product->blog) && $product->blog->published == 1) ? 'checked' : '' }}>
                                                    Check to publish this blog
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('products.index', Session::get('subdomain')) }}" class="btn btn-danger">Cancel</a>
        </div>
    </div>
</form>
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>
    <script src="{!! asset('js/tagify.min.js') !!}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script>
        // CKEDITOR.replace('editor-body');
        // CKEDITOR.replace('editor-blog-description');
        $('input[name=default_image_link]').change(function(){
            if($(this).val()){
                $('input[name=default_image]').val('');
                $('input[name=default_image]').attr('disabled', 'disabled');
            }else{
                $('input[name=default_image]').removeAttr('disabled');
            }
        });

        $('input[name=default_image]').change(function(){
            if($(this).val()){
                $('input[name=default_image_link]').val('');
                $('input[name=default_image_link]').attr('disabled', 'disabled');
            }else{
                $('input[name=default_image_link]').removeAttr('disabled');
            }
            
        });

        $('.editor').summernote({
            minHeight: 350,
            height: 'auto',
            focus: false,
            airMode: false,
            fontNames: [
                'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande',
                'Tahoma', 'Times New Roman', 'Verdana'
            ],
            fontNamesIgnoreCheck: [
                'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande',
                'Tahoma', 'Times New Roman', 'Verdana'
            ],
            dialogsInBody: true,
            dialogsFade: true,
            disableDragAndDrop: false,
            toolbar: [
                // [groupName, [list of button]]
                ['para', ['style', 'ul', 'ol', 'paragraph']],
                ["fontname", ["fontname"]],
                ['fontsize', ['fontsize']],
                ["color", ["color"]],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['height', ['height']],
                ["view", ["fullscreen", "codeview", "help"]],
                ["insert", ["link", "picture"]],
            ],
        });

        $(window).on('load', function(){
            $('input.note-image-input').css('opacity', '1');
            $('input.note-image-input').css('position', 'initial');
        })

        $('[data-toggle="tooltip"]').tooltip()
        $('[name=tags]').tagify();
        $('[name=tags]').tagify({duplicates : false});
        $('[name=meta_keywords]').tagify();
        $('[name=meta_keywords]').tagify({duplicates : false});

        var permalink = createPermalink($('input[name="permalink"]').val());
        $('input[name="permalink"]').val(permalink);
        $('span.permalink').text(permalink);

        function createPermalink(permalink){
            permalink = permalink.split(' ').join('-');
            permalink = permalink.replace(/[^a-z0-9\s]/gi, '-');
            permalink = permalink.split('_').join('-');
            permalink = permalink.replace(/-{2,}/g,'-');
            permalink = permalink.toLowerCase().trim();

            return permalink;
        }

        function remove(element){
            var option = $(element);
            var optionHtml = '<option value="'+$(element).parent().next('input').val()+'">'+$(element).parent().attr('data-name')+'</option>';

            $('select#category').append(optionHtml);
            option.parent().parent().remove();
        }
        
        $('.btn-delete-img').on('click', function(){
            var id = $(this).attr('data-id');
            var element = $(this).parent();

            axios.post("{{ route('products.image.delete', ['subdomain' => Session::get('subdomain')]) }}",  {
                image_id: id,
            })
            .then(function(response){
                if(response.data.msg == 'success'){
                    element.remove();
                    $.notify({
                        icon: "check_circle",
                        message: 'Image successfully removed',
                    },{
                        type: 'success'
                    });
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        });

        $('.product-permalink').on('keypress', function(event){
            var regex = new RegExp("^[a-zA-Z0-9\-_\b]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
           
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });

        $('.product-permalink').on('keyup', function(event){
            var permalink = createPermalink($(this).val());
            $('span.permalink').text(permalink);
            $('input[name="permalink"]').val(permalink);
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

        $('select[name=blog_type]').change(function(){
            var option = $(this).find(':selected');

            if(option.val() == 'manual_blog'){
                $('.manual_blog').show();
                $('.display-hidden').hide();
            }else{
                $('.display-hidden').show();
                $('.manual_blog').hide();
            }
        })
        
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
