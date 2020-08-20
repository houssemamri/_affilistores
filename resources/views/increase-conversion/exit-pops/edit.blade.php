@extends('master')

@section('page_title')
Edit Exit Pop
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Edit Exit Pop </strong></h4>
        <p class="card-category">Add new exit pop for your store</p>
    </div>
    <div class="card-body">
        <form action="{{ route('exitpops.edit', ['subdomain' => Session::get('subdomain'), 'id' => $id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6">
                        <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" name="status" type="checkbox" {{ $exitpop->status == 1 ? 'checked' : ''}}>
                            Check to use as default exit pop
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>    
                    </div>
                    <div class="form-group">
                        <label for="">Name <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter exit pop name.">info</i></label>
                        <input type="text" name="name" placeholder="Enter Name" value="{{ $exitpop->name }}" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label for="">Heading <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter heading of your exit pop. See preview on the right">info</i></label>
                        <input type="text" name="heading" placeholder="Enter Heading" value="{{ $exitpop->heading }}" class="form-control ">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Heading Font <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change heading font style. See preview on the right">info</i></label>
                                <select class="form-control" name="heading_font" id="heading-font">
                                    <option value="" selected="">Select Font</option>
                                    <option value="Georgia" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Georgia' ? 'selected' : '' }}>Georgia</option>
                                    <option value="Palatino Linotype" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Palatino Linotype' ? 'selected' : '' }}>Palatino Linotype</option>
                                    <option value="Times New Roman" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                                    <option value="Arial" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Arial' ? 'selected' : '' }}>Arial</option>
                                    <option value="Arial Black" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Arial Black' ? 'selected' : '' }}>Arial Black</option>
                                    <option value="Comic Sans MS" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Comic Sans MS' ? 'selected' : '' }}>Comic Sans MS</option>
                                    <option value="Impact" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Impact' ? 'selected' : '' }}>Impact</option>
                                    <option value="Tahoma" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Tahoma' ? 'selected' : '' }}>Tahoma</option>
                                    <option value="Verdana" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Verdana' ? 'selected' : '' }}>Verdana</option>
                                    <option value="Courier New" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Courier New' ? 'selected' : '' }}>Courier New</option>
                                    <option value="Lucida Console" {{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-family'} == 'Lucida Console' ? 'selected' : '' }}>Lucida Console</option>
                                </select>     
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Heading Font Size <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change heading font size. See preview on the right">info</i></label>
                                <select class="form-control" name="heading_font_size" id="heading-font-size">
                                    <option style="padding-right: 10px;" value="">Select Font Size</option>
                                    <option style="padding-right: 10px;" value="12px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '12px') ? 'selected' : '' }}>12px</option>
                                    <option style="padding-right: 10px;" value="14px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '14px') ? 'selected' : '' }}>14px</option>
                                    <option style="padding-right: 10px;" value="16px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '16px') ? 'selected' : '' }}>16px</option>
                                    <option style="padding-right: 10px;" value="18px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '18px') ? 'selected' : '' }}>18px</option>
                                    <option style="padding-right: 10px;" value="20px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '20px') ? 'selected' : '' }}>20px</option>
                                    <option style="padding-right: 10px;" value="24px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '24px') ? 'selected' : '' }}>24px</option>
                                    <option style="padding-right: 10px;" value="28px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '28px') ? 'selected' : '' }}>28px</option>
                                    <option style="padding-right: 10px;" value="30px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '30px') ? 'selected' : '' }}>30px</option>
                                    <option style="padding-right: 10px;" value="36px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '36px') ? 'selected' : '' }}>36px</option>
                                    <option style="padding-right: 10px;" value="40px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '40px') ? 'selected' : '' }}>40px</option>
                                    <option style="padding-right: 10px;" value="45px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '45px') ? 'selected' : '' }}>45px</option>
                                    <option style="padding-right: 10px;" value="50px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '50px') ? 'selected' : '' }}>50px</option>
                                    <option style="padding-right: 10px;" value="60px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) && json_decode($exitpop->styles)->heading->{'font-size'} == '60px') ? 'selected' : '' }}>60px</option>
                                </select>     
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Heading Color <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change heading color. See preview on the right">info</i></label>
                                <input type="color" name="heading_font_color" value="{{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->heading) ? json_decode($exitpop->styles)->heading->color : '' }}" class="form-control" id="heading-font-color">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Body <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter content of your exit pop. See preview on the right">info</i></label>
                        <input type="text" name="body" class="form-control" value="{{ $exitpop->body }}">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Body Font <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change body font style. See preview on the right">info</i></label>
                                <select class="form-control" name="body_font" id="body-font">
                                    <option value="" selected="">Select Font</option>
                                    <option value="Georgia" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Georgia') ? 'selected' : '' }}>Georgia</option>
                                    <option value="Palatino Linotype" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Palatino Linotype') ? 'selected' : '' }}>Palatino Linotype</option>
                                    <option value="Times New Roman" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Times New Roman') ? 'selected' : '' }}>Times New Roman</option>
                                    <option value="Arial" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Arial') ? 'selected' : '' }}>Arial</option>
                                    <option value="Arial Black" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Arial Black') ? 'selected' : '' }}>Arial Black</option>
                                    <option value="Comic Sans MS" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Comic Sans MS') ? 'selected' : '' }}>Comic Sans MS</option>
                                    <option value="Impact" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Impact') ? 'selected' : '' }}>Impact</option>
                                    <option value="Tahoma" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Tahoma') ? 'selected' : '' }}>Tahoma</option>
                                    <option value="Verdana" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Verdana') ? 'selected' : '' }}>Verdana</option>
                                    <option value="Courier New" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Courier New') ? 'selected' : '' }}>Courier New</option>
                                    <option value="Lucida Console" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-family'} == 'Lucida Console') ? 'selected' : '' }}>Lucida Console</option>
                                </select>   
                            </div>

                            <div class="col-lg-4 col-sm-12">
                                <label for="">Body Font Size</label>
                                <select class="form-control" name="body_font_size" id="body-font-size">
                                    <option style="padding-right: 10px;" value="">Select Font Size</option>
                                    <option style="padding-right: 10px;" value="12px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '12px') ? 'selected' : '' }}>12px</option>
                                    <option style="padding-right: 10px;" value="14px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '14px') ? 'selected' : '' }}>14px</option>
                                    <option style="padding-right: 10px;" value="16px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '16px') ? 'selected' : '' }}>16px</option>
                                    <option style="padding-right: 10px;" value="18px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '18px') ? 'selected' : '' }}>18px</option>
                                    <option style="padding-right: 10px;" value="20px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '20px') ? 'selected' : '' }}>20px</option>
                                    <option style="padding-right: 10px;" value="24px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '24px') ? 'selected' : '' }}>24px</option>
                                    <option style="padding-right: 10px;" value="28px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '28px') ? 'selected' : '' }}>28px</option>
                                    <option style="padding-right: 10px;" value="30px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '30px') ? 'selected' : '' }}>30px</option>
                                    <option style="padding-right: 10px;" value="36px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '36px') ? 'selected' : '' }}>36px</option>
                                    <option style="padding-right: 10px;" value="40px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '40px') ? 'selected' : '' }}>40px</option>
                                    <option style="padding-right: 10px;" value="45px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '45px') ? 'selected' : '' }}>45px</option>
                                    <option style="padding-right: 10px;" value="50px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '50px') ? 'selected' : '' }}>50px</option>
                                    <option style="padding-right: 10px;" value="60px" {{ (isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) && json_decode($exitpop->styles)->body->{'font-size'} == '60px') ? 'selected' : '' }}>60px</option>
                                </select>   
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="">Body Color <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change body font color. See preview on the right">info</i></label>
                                <input type="color" name="body_font_color" value="{{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->body) ? json_decode($exitpop->styles)->body->color : '' }}" class="form-control" id="body-font-color">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Button Text <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter button text. Max length 60 characters. See preview on the right">info</i></label>
                        <input type="text" name="button_text" class="form-control " value="{{ $exitpop->button_text }}">
                    </div>
                    <div class="form-group">
                        <label for="">Button Color <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Change button color. See preview on the right">info</i></label>
                        <input type="color" name="button_color" value="{{ isset($exitpop->styles) && isset(json_decode($exitpop->styles)->button) ? json_decode($exitpop->styles)->button->{'background-color'} : '#001f4f' }}" class="form-control ">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="">Email Content <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter email content. This content will be received by the user when they enter their email">info</i></label>
                        <textarea name="email_content" class="form-control" maxlength="60" id="editor-body">{!! $exitpop->content !!}</textarea>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3>Preview</h3>
                    <hr>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center"><strong class="heading"> {{ $exitpop->heading }} </strong></h4>
                            <p class="text-center"><input type="email" class="form-control input-block text-center" placeholder="Enter your email address"></p>
                            <p class="text-center body">{{ $exitpop->body }}</p>
                            <button class="btn btn-block btn-primary btn-text">{{ $exitpop->button_text }}</button>
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

        var styles = JSON.parse('{!! $exitpop->styles !!}');
        
        $.each(styles.button, function(index, value) {
            $('.btn-text').css(index, value);
        })

        $.each(styles.heading, function(index, value) {
            $('strong.heading').css(index, value);
        })

        $.each(styles.body, function(index, value) {
            $('p.body').css(index, value);
        })

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
