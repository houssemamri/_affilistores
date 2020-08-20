@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('page_title')
Create Newsletter
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Create Newsletter </strong></h4>
        <p class="card-category">Add new newsletter for your subscribers</p>
    </div>
    <div class="card-body">
        <form action="{{ route('newsletters.create', ['subdomain' => Session::get('subdomain')]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="20%"><strong>Subject <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter newsletter subject">info</i></strong></th>
                            <td>
                                <input type="text" name="subject" placeholder="Enter Subject" value="{{ old('subject') }}" class="form-control category-name">
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Content <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter newsletter content">info</i></th>
                            <td>
                                <textarea class="form-control" name="content" id="editor-body" rows="10">{{ old('content') }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <button type="submit" class="btn btn-primary" name="btn_save_sent">Save and Send</button>
            <a href="{{ route('newsletters.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
            
            <div class="clearfix"></div>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
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

    $(window).on('load', function(){
        $('input.note-image-input').css('opacity', '1');
        $('input.note-image-input').css('position', 'initial');
    })
</script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
