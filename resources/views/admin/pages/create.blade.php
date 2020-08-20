@extends('admin.master')

@section('page_title')
Pages
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong>Add New Page </strong></h4>
        <p class="card-category">Create your new page</p>
    </div>
    {{-- <div class="card-body">
        <div class="row">
            <div class="col-lg-12">	
                <div class="form-group">
                    <label class="bmd-label-floating">Select which page will be applied</label>
                    <select id="page_applied" class="form-control">
                        <option value="front_end" {{ ($type == 'front_end') ? 'selected' : '' }}>Front End</option>
                        <option value="member_page" {{ ($type == 'member') ? 'selected' : '' }}>Member Page</option>
                    </select>
                </div>
            </div>
        </div>
    </div> --}}

    @if($type == 'member')
    <div class="card-body ">
        <form action="{{ route('pages.add', $type) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Slug</label>
                        <br><strong><span>{{ URL::to('/') }}/admin/pages/<span class="slug"></span></span></strong>
                        <input type="hidden" name="slug" value="{{ old('title') }}" class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Icon </label>
                        <label for="">(Find icons in this link <a href="https://material.io/tools/icons/?style=baseline"> https://material.io/tools/icons/?style=baseline</a>)</label>
                     
                        <input type="text" name="icon" value="{{ old('icon') }}" class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Available for</label>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" id="checkAll"/>Check all
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        @foreach($memberships as $membership)
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" value="{{ $membership->id }}" name="available_for[]" type="checkbox">
                                {{ $membership->title }}
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Page Part</label>
                        <select name="page_part" id="page_part" class="form-control">
                            <option value="top_nav">Top Nav</option>
                            <option value="side_nav">Side Nav</option>
                            <option value="footer_nav">Footer Nav</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row part-page-row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Parent Page</label>
                        <select name="parent_page" id="parent_page" class="form-control">
                            <option value="">None</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Content</label>
                        <textarea class="form-control" name="content" id="editor-body">{{ old('content') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('pages.index') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
    @else
    <div class="card-body ">
        <form action="{{ route('pages.add', $type) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Slug</label>
                        <br><strong><span>{{ URL::to('/') }}/admin/pages/<span class="slug"></span></span></strong>
                        <input type="hidden" name="slug" value="{{ old('title') }}" class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Page Part</label>
                        <select name="page_part" id="" class="form-control">
                            <option value="none">None</option>
                            <option value="top_nav">Top Nav</option>
                            <option value="side_nav">Side Nav</option>
                            <option value="footer_nav">Footer Nav</option>
                        </select>
                    </div>
                </div>
            </div>

             <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Content</label>
                        <textarea class="form-control" name="content" id="editor-body">{{ old('content') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" name="frontEnd" class="btn btn-primary">Save</button>
            <a href="{{ route('pages.index') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
    @endif
   
    
</div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>
    <script>
        function createPermalink(permalink){
            permalink = permalink.split(' ').join('-');
            permalink = permalink.replace(/[^a-z0-9\s]/gi, '-');
            permalink = permalink.split('_').join('-');
            permalink = permalink.replace(/-{2,}/g,'-');
            permalink = permalink.toLowerCase().trim();

            return permalink;
        }

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

        $(window).on('load', function(){
            $('input.note-image-input').css('opacity', '1');
            $('input.note-image-input').css('position', 'initial');
        })
        
        if($('#page_part').val() == 'side_nav')
            $('.part-page-row').show();
        else
            $('.part-page-row').hide();
            
        $('#page_part').on('change', function(){
            var selected = $(this).val();

            if(selected == 'side_nav'){
                $('.part-page-row').show();
                $('#parent_page').attr('name', 'parent_page');
            }else{
                $('.part-page-row').hide();
                $('#parent_page').removeAttr('name');
            }
        });
        
        // $('#page_applied').on('change', function(){
        //     var selected = $(this).val();

        //     if(selected == 'front_end'){
        //         $('.front-end-page').show();
        //         $('.member-page').hide();
        //     }else{
        //         $('.member-page').show();
        //         $('.front-end-page').hide();

        //     }
        // });

        $('input[name="title"]').on('keyup', function(){
            var permalink = createPermalink($(this).val());
            $('span.slug').text(permalink.toLowerCase());
            $('input[name="slug"]').val(permalink.toLowerCase());
        });

        $('input[name="title"]').on('keyup', function(){
            var permalink = createPermalink($(this).val());
            $('span.slug').text(permalink.toLowerCase());
            $('input[name="slug"]').val(permalink.toLowerCase());
        });

        $("#checkAll").change(function () {
            $(this).parent().contents()[2].textContent = $(this).prop("checked") ? 'Uncheck all' : 'Check all';;
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection