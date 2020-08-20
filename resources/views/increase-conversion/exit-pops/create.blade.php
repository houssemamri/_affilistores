@extends('master')

@section('page_title')
Create Exit Pop
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Create Exit Pop </strong></h4>
        <p class="card-category">Add new exit pop for your store</p>
    </div>
    <div class="card-body">
        <form action="{{ route('exitpops.create', ['subdomain' => Session::get('subdomain')]) }}" method="POST" enctype='multipart/form-data'>
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6">
                        <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" name="status" type="checkbox" >
                            Check to use as default exit pop
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="">Name <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter exit pop name.">info</i></label>
                        <input type="text" name="name" placeholder="Enter Name" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label for="">Heading <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter heading of your exit pop. See preview on the right">info</i></label>
                        <input type="text" name="heading" placeholder="Enter Heading" class="form-control ">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Heading Font <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change heading font style. See preview on the right">info</i></label>
                                <select class="form-control" name="heading_font" id="heading-font">
                                    <option value="" selected="">Select Font</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Palatino Linotype">Palatino Linotype</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Arial">Arial</option>
                                    <option value="Arial Black">Arial Black</option>
                                    <option value="Comic Sans MS">Comic Sans MS</option>
                                    <option value="Impact">Impact</option>
                                    <option value="Tahoma">Tahoma</option>
                                    <option value="Verdana">Verdana</option>
                                    <option value="Courier New">Courier New</option>
                                    <option value="Lucida Console">Lucida Console</option>
                                </select>   
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Heading Font Size <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change heading font size. See preview on the right">info</i></label>
                                <select class="form-control" name="heading_font_size" id="heading-font-size">
                                    <option style="padding-right: 10px;" value="" selected="">Select Font Size</option>
                                    <option style="padding-right: 10px;" value="12px">12px</option>
                                    <option style="padding-right: 10px;" value="14px">14px</option>
                                    <option style="padding-right: 10px;" value="16px">16px</option>
                                    <option style="padding-right: 10px;" value="18px">18px</option>
                                    <option style="padding-right: 10px;" value="20px">20px</option>
                                    <option style="padding-right: 10px;" value="24px">24px</option>
                                    <option style="padding-right: 10px;" value="28px">28px</option>
                                    <option style="padding-right: 10px;" value="30px">30px</option>
                                    <option style="padding-right: 10px;" value="36px">36px</option>
                                    <option style="padding-right: 10px;" value="40px">40px</option>
                                    <option style="padding-right: 10px;" value="45px">45px</option>
                                    <option style="padding-right: 10px;" value="50px">50px</option>
                                    <option style="padding-right: 10px;" value="60px">60px</option>
                                </select>   
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Heading Color <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change heading color. See preview on the right">info</i></label>
                                <input type="color" name="heading_font_color" class="form-control" id="heading-font-color">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                        <label for="">Image <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Upload image content of your exit pop. See preview on the right">info</i></label>
                            <input type="file" name="image" accept="image/*" class="form-control-file">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Body <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter content of your exit pop. See preview on the right">info</i></label>
                        <input type="text" name="body" class="form-control ">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Body Font <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change body font style. See preview on the right">info</i></label>
                                <select class="form-control" name="body_font" id="body-font">
                                    <option value="" selected="">Select Font</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Palatino Linotype">Palatino Linotype</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Arial">Arial</option>
                                    <option value="Arial Black">Arial Black</option>
                                    <option value="Comic Sans MS">Comic Sans MS</option>
                                    <option value="Impact">Impact</option>
                                    <option value="Tahoma">Tahoma</option>
                                    <option value="Verdana">Verdana</option>
                                    <option value="Courier New">Courier New</option>
                                    <option value="Lucida Console">Lucida Console</option>
                                </select>   
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Body Font Size</label>
                                <select class="form-control" name="body_font_size" id="body-font-size">
                                    <option style="padding-right: 10px;" value="" selected="">Select Font Size</option>
                                    <option style="padding-right: 10px;" value="12px">12px</option>
                                    <option style="padding-right: 10px;" value="14px">14px</option>
                                    <option style="padding-right: 10px;" value="16px">16px</option>
                                    <option style="padding-right: 10px;" value="18px">18px</option>
                                    <option style="padding-right: 10px;" value="20px">20px</option>
                                    <option style="padding-right: 10px;" value="24px">24px</option>
                                    <option style="padding-right: 10px;" value="28px">28px</option>
                                    <option style="padding-right: 10px;" value="30px">30px</option>
                                    <option style="padding-right: 10px;" value="36px">36px</option>
                                    <option style="padding-right: 10px;" value="40px">40px</option>
                                    <option style="padding-right: 10px;" value="45px">45px</option>
                                    <option style="padding-right: 10px;" value="50px">50px</option>
                                    <option style="padding-right: 10px;" value="60px">60px</option>
                                </select>   
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Body Color <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change body font color. See preview on the right">info</i></label>
                                <input type="color" name="body_font_color" class="form-control" value="#001f4f" id="body-font-color">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Button Text <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter button text. Max length 60 characters. See preview on the right">info</i></label>
                        <input type="text" name="button_text" maxlength="50" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label for="">Button Color <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change button color. See preview on the right">info</i></label>
                        <input type="color" name="button_color" class="form-control ">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="">Email Content <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter email content. This content will be received by the user when they enter their email">info</i></label>
                        <textarea name="email_content" class="form-control" id="editor-body"></textarea>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3>Preview</h3>
                    <hr>
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-center"><strong class="heading"> </strong></h4>
                            <img src="" alt="" id="image" class="img-fluid display-hidden">
                            <p class="text-center"><input type="email" class="form-control input-block text-center" placeholder="Enter your email address"></p>
                            <p class="text-center body"></p>
                            <button class="btn btn-block btn-primary btn-text"></button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('exitpops.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
            
            <div class="clearfix"></div>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>
    
    <script>
        $('[data-toggle="tooltip"]').tooltip()
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
        
        function readURL(input, element) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $(element).attr('src', e.target.result);
                    $(element).show();
                }

                reader.readAsDataURL(input.files[0]);
            }else{
                $(element).attr('src', '');
                $(element).hide();
            }
        }

        $("input[name=image]").change(function(){
            readURL(this, '#image');
        });

        $(window).on('load', function(){
            $('input.note-image-input').css('opacity', '1');
            $('input.note-image-input').css('position', 'initial');
        })

        $('input[name=heading]').on('keypress keydown keyup', function(){
            $('strong.heading').text($(this).val())
        });

        $('input[name=body]').on('keypress keydown keyup', function(){
            $('p.body').text($(this).val())
        });

        $('input[name=button_text]').on('keypress keydown keyup', function(){
            $('.btn-text').text($(this).val())
        });

        $('input[name=button_color]').change(function(){
            $('.btn-text').css('background-color', $(this).val())
        })

        $('#heading-font, #heading-font-size, #heading-font-color').change(function(){
            if($(this).attr('id') == 'heading-font')
                $('strong.heading').css('font-family', $(this).val());
    
            if($(this).attr('id') == 'heading-font-size')
                $('strong.heading').css('font-size', $(this).val());

            if($(this).attr('id') == 'heading-font-color')
                $('strong.heading').css('color', $(this).val());
        })

        $('#body-font, #body-font-size, #body-font-color').change(function(){
            if($(this).attr('id') == 'body-font')
                $('p.body').css('font-family', $(this).val());
    
            if($(this).attr('id') == 'body-font-size')
                $('p.body').css('font-size', $(this).val());

            if($(this).attr('id') == 'body-font-color')
                $('p.body').css('color', $(this).val());
        })
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
