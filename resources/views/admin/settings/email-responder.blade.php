@extends('admin.master')

@section('page_title')
Email Responders
@endsection

@section('content')
<form action="#" method="POST">
    {!! csrf_field() !!}
    @foreach($emailResponders as $email)
    <input type="hidden" name="id[]" value="{{ Crypt::encrypt($email->id) }}" class="form-control" >
    
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> {{ $email->name }} </strong></h4>
            <p class="card-category">{{ $email->description }}</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">From</label>
                        <input type="text" name="from[]" value="{{ $email->from }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Reply</label>
                        <input type="text" name="reply[]" value="{{ $email->reply }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">To</label>
                        <input type="text" name="to[]" value="{{ $email->to }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Subject</label>
                        <input type="text" name="subject[]" value="{{ $email->subject }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Content</label>
                        <textarea class="form-control editor" name="content[]" id="editor-body-{{ $loop->index }}">{!! $email->body !!}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="card-body">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <div class="clearfix"></div>
    </div>
</form>

@endsection

@section('custom-scripts')
    <!-- <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script> -->
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>

    <script>
        // CKEDITOR.replace('editor-body-0');
        // CKEDITOR.replace('editor-body-1');
        // CKEDITOR.replace('editor-body-2');
        // CKEDITOR.replace('editor-body-3');

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
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
