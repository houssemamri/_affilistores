@extends('admin.master')

@section('page_title')
Add New Notifcation
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Add New Notifcation </strong></h4>
        <p class="card-category">Send new notifcation to your member</p>
    </div>
    <div class="card-body">
        <form action="{{ route('settings.notification.add') }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Content</label>
                        <textarea class="form-control" name="content" value="{{ old('content') }}" id="editor-body"></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('settings.notification.index') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <!-- <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script> -->
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
