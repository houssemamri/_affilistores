@extends('admin.master')

@section('page_title')
Bonuses
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong>Add New Bonus </strong></h4>
        <p class="card-category">Upload bonus for your members</p>
    </div>
    <div class="card-body">
        <form action="{{ route('bonuses.add') }}" method="POST" enctype="multipart/form-data" id="bonusForm" novalidate>
            {!! csrf_field() !!}
            <table class="table">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        </td>
                    </tr>
                    <tr>
                        <th>Available for</th>
                        <td class="check">
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
                                    <input class="form-check-input available" value="{{ $membership->id }}" name="available_for[]" type="checkbox">
                                    {{ $membership->title }}
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>
                            <textarea class="form-control" name="description" id="editor-description" required>{{ old('description') }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>Cover Image </th>
                        <td>
                            <input type="file" class="form-control-file" name="image" id="image" required>
                            <br><small class="text-muted">(Maximum of 2MB)</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Upload Product File </th>
                        <td>
                            <input type="file" class="form-control-file" name="product_file" id="file" required>
                            <br><small class="text-muted">(Maximum of 200MB)</small>
                            
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 2%">0%</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary btn-save">Save</button>
            <a href="{{ route('bonuses.index') }}" class="btn btn-danger btn-cancel">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection

@section('custom-scripts')
    <!-- <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script> -->
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>
    <script src="{{ asset('js/jquery.form.js') }}"></script>
    
    <script>
        // var description = CKEDITOR.replace('editor-description');
        var validImageTypes = ["image/gif", "image/jpeg", "image/png"];

        var description = $('#editor-description').summernote({
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

        $("#checkAll").change(function () {
            $(this).parent().contents()[2].textContent = $(this).prop("checked") ? 'Uncheck all' : 'Check all';;
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });

        $('input[name=image]').change(function(){
            if($.inArray(this.files[0].type, validImageTypes) < 0){ 
                $(this).val('');
                $('.btn-save').attr('disabled', '');
                errorNotify('Cover Image must be an image file type')
            }else if(this.files[0].size > 2000000){ //2MB
                $(this).val('');
                $('.btn-save').attr('disabled', '');
                errorNotify('Cover Image limit is just 2MB')
            }else{
                $('.btn-save').removeAttr('disabled');
            }
        })
        
        $('input[name=product_file]').change(function(){
			if(this.files[0].size > 200000000){ //200MB
                $(this).val('');
                $('.btn-save').attr('disabled', '');
                errorNotify('File limit is just 200MB')
            }else{
                $('.btn-save').removeAttr('disabled');
            }
		})

        $('#bonusForm').submit(function(e){
            e.preventDefault();
            var available = 0;

            $.each($('input.available'), function(index, element){
                if($(this).prop('checked') == true)
                    available++
            })

            if(!$('input[name=name]').val())
                errorNotify('Name is a required field');

            if(available == 0)
                errorNotify('Please select atleast one available for');

            if(!description.summernote('code') || description.summernote('code') == '<p><br></p>')
                errorNotify('Description is a required field');

            if(!$('input[name=image]').val())
                errorNotify('Cover Image is a required');

            if(!$('input[name=product_file]').val())
                errorNotify('Product File is a required');

            if($('input[name=name]').val() && description.summernote('code') && $('input[name=image]').val() && $('input#file').val() && available > 0){
                $('textarea[name=description]').val(description.summernote('code'));

                $('.btn-save').attr('disabled', '');
                $('.btn-cancel').addClass('disabled');

                $(this).ajaxSubmit({
                    beforeSubmit: function(){
                        $('.progress-bar').width('2%');
                    },
                    uploadProgress: function(event, position, total, percentComplete){
                        $('.progress-bar').width(percentComplete + '%');
                        $('.progress-bar').text(percentComplete + '%');
                    },
                    success: function(response){
                        $('.btn-save').removeAttr('disabled');
                        $('.btn-cancel').removeClass('disabled');

                        $.notify({
                            icon: "check_circle",
                            message: response.msg,
                        },{
                            type: 'success'
                        });

                        window.location = response.url
                    },
                    error: function(error){
                        console.log(error);

                        $('.btn-save').removeAttr('disabled');
                        $('.btn-cancel').removeClass('disabled');
                    },
                    resertForm: true
                })

                return false
            }
        })

        function errorNotify(msg){
            $.notify({
                icon: "error",
                message: msg,
            },{
                type: 'danger'
            });
        }
    </script>
@endsection
