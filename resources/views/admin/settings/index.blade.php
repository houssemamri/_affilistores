@extends('admin.master')

@section('page_title')
General
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> General </strong></h4>
        <p class="card-category">Manage common settings</p>
    </div>
    <div class="card-body">
    <form action="{{ route('settings.general') }}" method="POST" enctype="multipart/form-data">
           {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table table-fixed general-settings">
                    <tbody>
                        @foreach($setups as $setup)
                            @if($setup->key !== 'campaign_id')  
                            <tr>
                                <th width="20%">{{ $setup->name }}</th>
                                <th {{ ((isset($setup->value) && $setup->value != "") && $setup->type == 'file') != "" ? '' : 'colspan = 2'}}>
                                    @if($setup->type == 'text')
                                        <input type="text" class="form-control" name="{{ $setup->key }}" value="{{ $setup->value }}">
                                    @elseif($setup->type == 'file')
                                        <input type="file" name="{{ $setup->key }}" accept="image/*" class="form-control-file" id="logo" value="{{ $setup->value }}">
                                    @elseif($setup->type == 'textarea')
                                        <textarea class="form-control" name="{{ $setup->key }}" id="editor-{{ $setup->key }}">{!! $setup->value !!}</textarea>
                                    @endif
                                </th>
                                @if((isset($setup->value) && $setup->value != "") && $setup->type == 'file')
                                    <th>
                                        <img src="{{ asset('img/uploads/' . $setup->value) }}" alt="" class="img-fluid {!! $setup->key == 'favicon' ? 'favicon' : '' !!}">
                                    </th>
                                @endif
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <div class="clearfix"></div>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <!-- <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script> -->
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>

    <script>
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

        $('textarea').each(function(index) {

            // CKEDITOR.replace($(this).attr('id'));

            $('#' + $(this).attr('id')).summernote({
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