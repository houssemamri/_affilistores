@extends('master')

@section('page_title')
Update Blog
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Update Blog </strong></h4>
        <p class="card-category">Update blog content</p>
    </div>
    <div class="card-body">
        <form action="{{ route('blogs.edit', ['subdomain' => Session::get('subdomain'), 'id' => $id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Title</label>
                        <input type="text" name="title" value="{{ $blog->title }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Category</label>
                        <select name="category" id="" class="form-control">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == $blog->blog_category_id ? 'selected' : ''}}>{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Post</label>
                        <p><a href="#" class="btn btn-primary btn-spin">Spin the Content</a></label></p>
                        <textarea class="form-control" name="post" id="editor-body">{!! $blog->post !!}</textarea>
                    </div>
                </div>
            </div>

            <div class="spun-row">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Spun Version <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="You can copy the spun version of the blog post content.">info</i></label>
                            <textarea class="form-control" id="editor-spun">{!! old('post') !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Product Category <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose product category to get products for this blog feed.">info</i></label>
                        <select name="product_category" id="" class="form-control">
                            @foreach($productCategories as $productCategory)
                            <option value="{{ $productCategory->id }}" {{ $blog->category_id == $productCategory->id ? 'selected' : ''}}>{{ $productCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <button type="submit" class="btn btn-primary" name="save_publish">Save & Publish</button>
            <a href="{{ route('blogs.index', Session::get('subdomain')) }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
        $('[data-toggle="tooltip"]').tooltip({ html: true })
    </script>
    <script src="{!! asset('js/axios.min.js') !!}"></script>
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>
    <script>
        // CKEDITOR.replace('editor-body');
        $('#editor-body').summernote({
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

         $('#editor-spun').summernote({
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

        $('.btn-spin').on('click', function(){
            var url = '{{ route("api.spin.content") }}';
            var text = $("#editor-body").summernote("code");

            if(text){
                axios.post(url, {
                text: text
                })
                .then(function (response) {
                    if(response.data.success == 'false'){
                        console.log(response.data)
                        $.notify({
                            icon: "error",
                            message: response.data.error,
                        },{
                            type: 'danger'
                        });
                    }else if(response.data.success == 'true'){
                        console.log(response.data)
                        $('.spun-row').show();
                        $('#editor-spun').summernote('code', response.data.output);
                    }
                })
                .catch(function (error) {
                    $.notify({
                        icon: "error",
                        message: 'Something went wrong!',
                    },{
                        type: 'danger'
                    });
                });
            }else{
                $.notify({
                    icon: "error",
                    message: 'Please enter content to spin.',
                },{
                    type: 'danger'
                });
            }
        })
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
