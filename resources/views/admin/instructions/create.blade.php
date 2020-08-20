@extends('admin.master')

@section('page_title')
Add New Instructions
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Add New Instructions </strong></h4>
        <p class="card-category">Add instruction to your market places</p>
    </div>
    <div class="card-body">
        <form action="{{ route('instructions.add') }}" method="POST">
            {!! csrf_field() !!}
 
            <div class="form-group">
                <label class="bmd-label-floating">Market Place</label>
                <select name="market_place" id="" class="form-control">
                    @foreach($marketPlaces as $key => $marketPlace)
                    <option value="{{ $key }}" {{ old('market_place') == $key ? 'selected' : '' }}>{{ $marketPlace }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="bmd-label-floating">Instructions</label>
            </div>

            <div class="form-group">
                <textarea class="form-control" name="instructions" id="editor-body">{{ old('instructions') }}</textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('instructions.index') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>

    <script>
        $('input[name=never_expire]').change(function(){
            if($(this).is(":checked")){
                $('input[name=expiry_date]').attr('disabled', 'disabled');
            }else{
                $('input[name=expiry_date]').removeAttr('disabled');
            }
        });
    </script>

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
