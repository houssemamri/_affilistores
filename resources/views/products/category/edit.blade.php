@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('css/image-picker.css') !!}" />
@endsection

@section('page_title')
Edit Category
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Edit Category </strong></h4>
        <p class="card-category">Update category for your products</p>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.edit', ['id' => $id, 'subdomain' => Session::get('subdomain')]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="20%"><strong>Name <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your category's name">info</i></strong></th>
                            <td>
                                <input type="text" name="name" placeholder="Enter Category Name" value="{{ $category->name }}" class="form-control category-name">
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Image <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Upload category image to be displayed on home page. Recommended size 250 x 250 pixel">info</i></th>
                            <td>
                                @if(isset($category->image) && $category->image !== '')
                                    <img src="{{ asset('img/uploads/' . Session::get('subdomain') . '/categories/' . $category->image ) }}" alt="" class="img-fluid">
                                    <hr>
                                @endif
                               
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info btn-choose-file" type="button">Upload image</button>
                                    <button class="btn btn-sm btn-info btn-choose-pixabay" type="button">Choose from Pixabay</button>
                                </div>

                                <div class="pixabay">
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
                                                            @foreach($pixabayCategories as $pixabayCategory)
                                                            <option value="{{ $pixabayCategory }}">{{ ucwords($pixabayCategory) }}</option>
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
                                                            <button type="button" class="btn btn-primary pixabaySearch">Search</button>    
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
                                
                                <div class="choose-file">
                                    <input type="file" accept="image/*" class="form-control-file" id="logo" >
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Permalink <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set the link of your category">info</i></strong></th>
                            <td class="permalink">
                               <span>{{ URL::to('/') }}/category/<span class="permalink">{{ $category->permalink }}</span></span> 
                               <input type="hidden" value="{{ $category->permalink }}" name="permalink">
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Description <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your category's description ">info</i></strong></th>
                            <td>
                                <textarea name="description" class="form-control"  cols="1" rows="5">{{ $category->description }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Meta Title <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add Meta Title of your category">info</i></strong></th>
                            <td>
                                <input type="text" name="meta_title" value="{{ $category->meta_title }}" placeholder="Enter Meta Title" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Meta Description <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add Meta Description of your category">info</i></strong></th>
                            <td>
                                <textarea name="meta_description" class="form-control" cols="1" rows="5">{{ $category->meta_description }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Meta Keywords <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add Meta Keywords of your category. Add comma to enter tags or 'Enter' key in keyboard ">info</i></strong></th>
                            <td>
                                <input type="text" name="meta_keywords" value="{{ $category->meta_keywords }}" placeholder="Enter Meta Keywords" >
                            </td>
                        </tr>
                        <tr>
                            <th withd="20%">Robots Meta NoIndex <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you don't want your category be rank in search engines">info</i></th>
                            <td>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="robots_meta_no_index" {{ ($category->robots_meta_no_index) ? 'checked' : ''}}>
                                    Set to active
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            </td>
                        </tr>
                        <tr>
                            <th withd="20%">Robots Meta NoFollow <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you don't want your category be rank in search engines">info</i></th>
                            <td>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="robots_meta_no_follow" {{ ($category->robots_meta_no_follow) ? 'checked' : ''}}>
                                    Set to active
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            </td>
                        </tr>
                        <tr>
                            <th withd="20%">Status <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you want to use this category">info</i></th>
                            <td>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="status" {{ ($category->status) ? 'checked' : ''}}>
                                    Set to active
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('categories.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
            
            <div class="clearfix"></div>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{!! asset('js/tagify.min.js') !!}"></script>
    <script src="{!! asset('js/axios.min.js') !!}"></script>
    <script src="{!! asset('js/image-picker.js') !!}"></script>
    <script>
        function createPermalink(permalink){
            permalink = permalink.split(' ').join('-');
            permalink = permalink.replace(/[^a-z0-9\s]/gi, '-');
            permalink = permalink.split('_').join('-');
            permalink = permalink.replace(/-{2,}/g,'-');
            permalink = permalink.toLowerCase().trim();

            return permalink;
        }

        $('.category-name').on('keyup', function(){
            $('span.permalink').text($(this).val().toLowerCase());
            $('input[name="permalink"]').val($(this).val().toLowerCase());
        });

        $('[data-toggle="tooltip"]').tooltip()
        $('[name=meta_keywords]').tagify();
        $('[name=meta_keywords]').tagify({duplicates : false});

        $('.category-name').on('keyup', function(event){
            var permalink = createPermalink($(this).val());
            $('span.permalink').text(permalink);
            $('input[name="permalink"]').val(permalink);
        });

        $('.btn-choose-file').on('click', function(){
            $('.choose-file').show();
            $('input[type=file]').attr('name', 'image');
            $('.pixabay').hide();
            $('.pixabay-results').empty();
        });

        $('.btn-choose-pixabay').on('click', function(){
            $('.choose-file').hide();
            $('.pixabay').show();
            $('input[type=file]').removeAttr('name');
        });

        $('.pixabaySearch').on('click', function(){
            var pixabay_key = '{{ $site["pixabay"] }}';
            var html = '';

            var params = { 
                key: pixabay_key, 
                q: encodeURI($('#query').val()), 
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
                    html += '<select name="pixabayImage" id="pixabay-images">';
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
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
