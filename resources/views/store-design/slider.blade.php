@extends('master')

@section('page_title')
Slider Settings
@endsection

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{!! asset('css/image-picker.css') !!}" />
@endsection

@section('content')
<div class="card">
  <div class="card-header card-header-primary">
    <div class="nav-tabs-navigation">
      <div class="nav-tabs-wrapper">
        <ul class="nav nav-tabs" data-tabs="tabs">
            @foreach($sliders as $slider)
            <li class="nav-item">
                <a class="nav-link {{ ($loop->iteration == 1) ? 'active show' : '' }}" href="#slide{{ $loop->iteration }}" data-toggle="tab">
                    Slide {{ $loop->iteration }}
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endforeach
        </ul>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div id="myTabContent" class="tab-content">
        @foreach($sliders as $slider)
            <div class="tab-pane fade {{ ($loop->iteration == 1) ? 'active show' : '' }}"" id="slide{{ $loop->iteration }}">
                <form action="{{ route('slider', ['subdomain' => Session::get('subdomain')]) }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <input type="hidden" name="sliderId" value="{{ Crypt::encrypt($slider->id) }}">
                <h3><strong>Slide {{ $loop->iteration }} Settings </strong></h3>
                <div class="table-responsive">
                    <table class="table table-fixed">
                        <tbody>
                            <tr>
                                <th>Enable Slide: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you want to display this slide">info</i></th>
                                <th>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="status" type="checkbox" {{ ($slider->slider->status == 1) ? 'checked' : ''}}>
                                            Check to enable this slide
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th> </th>
                                <th>
                                    @if(isset($slider->slider->image))
                                        <img src="{{ asset('img/uploads/'.$store->subdomain.'/slider/' . $slider->slider->image) }}" alt="" class="img-fluid"></th>
                                    @endif
                            </tr>
                            <tr>
                                <th>Slide Image: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Upload image for this slide. Recommended size 1280 x 720 pixels or with same aspect ratio of 16:9 (e.g 768 x 576, 1024 x 576, 1920 x 1080) .">info</i></th>
                                <th colspan=2> 
                                    <div class="btn-group">
                                            <button class="btn btn-sm btn-info btn-choose-file" type="button">Upload image</button>
                                            <button class="btn btn-sm btn-info btn-choose-pixabay" type="button">Choose from Pixabay</button>
                                        </div>

                                        <div class="pixabay">
                                            <div class="card">
                                                <div class="card-header card-header-primary">
                                                    <h4 class="card-title"><strong> Pixabay </strong></h4>
                                                    <p class="card-category">Search free images in Pixabay</p>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td>Search </td>
                                                                <td>
                                                                    <input type="text" class="form-control" id="query">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Image Type </td>
                                                                <td>
                                                                    <select id="image_type" class="form-control">
                                                                        <option value="all">All</option>
                                                                        <option value="images">Images</option>
                                                                        <option value="photo">Photo</option>
                                                                        <option value="illustration">Illustration</option>
                                                                        <option value="vector">Vector</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Category </td>
                                                                <td>
                                                                    <select id="category" class="form-control">
                                                                    @foreach($pixabayCategories as $pixabayCategory)
                                                                    <option value="{{ $pixabayCategory }}">{{ ucwords($pixabayCategory) }}</option>
                                                                    @endforeach
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Order </td>
                                                                <td>
                                                                    <select id="order" class="form-control">
                                                                        <option value="popular">Popular</option>
                                                                        <option value="latest">Latest</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <button type="button" class="btn btn-primary pixabaySearch">Search</button> 
                                                                </td>
                                                                <td>
                                                                    
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="card-body pixabay-results">

                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="choose-file">
                                            <input type="file" accept="image/*" class="form-control-file" id="logo" >
                                        </div>
                                </th>
                            </tr>
                            <tr>
                                <th>Main Tagline: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add main tagline for this slide">info</i></th>
                                <th><input type="text" name="main_tagline" value="{{ $slider->slider->main_tagline }}" placeholder="Enter Main Tagline" class="form-control"></th>
                            </tr>
                            <tr>
                                <th>Main Tagline's Font Styling: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Select font size for the main tagline. Leave blank to set default size.">info</i></th>
                                <th>
                                    <select class="form-control" name="main_tagline_font_size" id="main-font-styling">
                                        <option style="padding-right: 10px;" value="" selected="">Select Font Size</option>
                                        <option style="padding-right: 10px;" value="12px" {{ $slider->slider->main_tagline_font_size == '12px' ? 'selected' : '12px'}}>12px</option>
                                        <option style="padding-right: 10px;" value="14px" {{ $slider->slider->main_tagline_font_size == '14px' ? 'selected' : '14px'}}>14px</option>
                                        <option style="padding-right: 10px;" value="16px" {{ $slider->slider->main_tagline_font_size == '16px' ? 'selected' : '16px'}}>16px</option>
                                        <option style="padding-right: 10px;" value="18px" {{ $slider->slider->main_tagline_font_size == '18px' ? 'selected' : '18px'}}>18px</option>
                                        <option style="padding-right: 10px;" value="20px" {{ $slider->slider->main_tagline_font_size == '20px' ? 'selected' : '20px'}}>20px</option>
                                        <option style="padding-right: 10px;" value="24px" {{ $slider->slider->main_tagline_font_size == '24px' ? 'selected' : '24px'}}>24px</option>
                                        <option style="padding-right: 10px;" value="28px" {{ $slider->slider->main_tagline_font_size == '28px' ? 'selected' : '28px'}}>28px</option>
                                        <option style="padding-right: 10px;" value="30px" {{ $slider->slider->main_tagline_font_size == '30px' ? 'selected' : '30px'}}>30px</option>
                                        <option style="padding-right: 10px;" value="36px" {{ $slider->slider->main_tagline_font_size == '36px' ? 'selected' : '36px'}}>36px</option>
                                        <option style="padding-right: 10px;" value="40px" {{ $slider->slider->main_tagline_font_size == '40px' ? 'selected' : '40px'}}>40px</option>
                                        <option style="padding-right: 10px;" value="45px" {{ $slider->slider->main_tagline_font_size == '45px' ? 'selected' : '45px'}}>45px</option>
                                        <option style="padding-right: 10px;" value="50px" {{ $slider->slider->main_tagline_font_size == '50px' ? 'selected' : '50px'}}>50px</option>
                                        <option style="padding-right: 10px;" value="60px" {{ $slider->slider->main_tagline_font_size == '60px' ? 'selected' : '60px'}}>60px</option>
                                    </select>   
                                </th>
                                <th> </th>
                            </tr>
                            <tr>
                                <th>Sub Tagline: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add sub tagline for this slide">info</i></th>
                                <th>
                                    <input type="text" name="sub_tagline" value="{{ $slider->slider->sub_tagline }}" placeholder="Enter Sub Tagline" class="form-control">
                                </th>
                                <th> </th>
                            </tr>
                            <tr>
                                <th>Sub Tagline's Font Styling: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Select font size for the sub tagline. Leave blank to set default size.">info</i></th>
                                <th>
                                    <select class="form-control" name="sub_tagline_font_size" id="main-font-styling">
                                        <option style="padding-right: 10px;" value="" selected="">Select Font Size</option>
                                        <option style="padding-right: 10px;" value="12px" {{ $slider->slider->sub_tagline_font_size == '12px' ? 'selected' : ''}}>12px</option>
                                        <option style="padding-right: 10px;" value="14px" {{ $slider->slider->sub_tagline_font_size == '14px' ? 'selected' : ''}}>14px</option>
                                        <option style="padding-right: 10px;" value="16px" {{ $slider->slider->sub_tagline_font_size == '16px' ? 'selected' : ''}}>16px</option>
                                        <option style="padding-right: 10px;" value="18px" {{ $slider->slider->sub_tagline_font_size == '18px' ? 'selected' : ''}}>18px</option>
                                        <option style="padding-right: 10px;" value="20px" {{ $slider->slider->sub_tagline_font_size == '20px' ? 'selected' : ''}}>20px</option>
                                        <option style="padding-right: 10px;" value="24px" {{ $slider->slider->sub_tagline_font_size == '24px' ? 'selected' : ''}}>24px</option>
                                        <option style="padding-right: 10px;" value="28px" {{ $slider->slider->sub_tagline_font_size == '28px' ? 'selected' : ''}}>28px</option>
                                        <option style="padding-right: 10px;" value="30px" {{ $slider->slider->sub_tagline_font_size == '30px' ? 'selected' : ''}}>30px</option>
                                        <option style="padding-right: 10px;" value="36px" {{ $slider->slider->sub_tagline_font_size == '36px' ? 'selected' : ''}}>36px</option>
                                        <option style="padding-right: 10px;" value="40px" {{ $slider->slider->sub_tagline_font_size == '40px' ? 'selected' : ''}}>40px</option>
                                        <option style="padding-right: 10px;" value="45px" {{ $slider->slider->sub_tagline_font_size == '45px' ? 'selected' : ''}}>45px</option>
                                        <option style="padding-right: 10px;" value="50px" {{ $slider->slider->sub_tagline_font_size == '50px' ? 'selected' : ''}}>50px</option>
                                        <option style="padding-right: 10px;" value="60px" {{ $slider->slider->sub_tagline_font_size == '60px' ? 'selected' : ''}}>60px</option>
                                    </select>   
                                </th>
                            </tr>
                            <tr>
                                <th>CTA Button1 Text: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set button text. Max length 150 characters.">info</i></th>
                                <th><input type="text" name="cta_button_one_text" value="{{ $slider->slider->cta_button_one_text }}" placeholder="Enter CTA Button1 Text" maxlength="150" class="form-control"></th>
                            </tr>
                            <tr>
                                <th>CTA Button1 Link: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set button link. If leave blank button will not display.">info</i></th>
                                <th><input type="text" name="cta_button_one_link" value="{{ $slider->slider->cta_button_one_link }}" placeholder="Enter CTA Button1 Link" class="form-control"></th>
                            </tr>
                            <tr>
                                <th>CTA Button2 Text: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set button text. Max length 150 characters.">info</i></th>
                                <th><input type="text" name="cta_button_two_text" value="{{ $slider->slider->cta_button_two_text }}" placeholder="Enter CTA Button2 Text" maxlength="150" class="form-control"></th>
                            </tr>
                            <tr>
                                <th>CTA Button2 Link: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set button link. If leave blank button will not display.">info</i></th>
                                <th><input type="text" name="cta_button_two_link" value="{{ $slider->slider->cta_button_two_link }}" placeholder="Enter CTA Button2 Link" class="form-control"></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">Save Slide Settings</button>
                <div class="clearfix"></div>
                </form>
            </div>
        @endforeach
    </div>
  </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{!! asset('js/axios.min.js') !!}"></script>
    <script src="{!! asset('js/image-picker.js') !!}"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip()

        $('.btn-choose-file').on('click', function(){
            $(this).parent().parent().find('div.choose-file').show();
            $('input[type=file]').attr('name', 'image');
            $(this).parent().parent().find('div.pixabay').hide();
            $(this).parent().parent().find('div.pixabay').find('div.pixabay-results').empty();
        });

        $('.btn-choose-pixabay').on('click', function(){
            $(this).parent().parent().find('div.choose-file').hide();
            $(this).parent().parent().find('div.pixabay').show();
            $(this).parent().parent().find('div.pixabay').find('div.pixabay-results').removeAttr('name');
        });

        $('.pixabaySearch').on('click', function(){
            var pixabay_key = '{{ $site["pixabay"] }}';
            var html = '';
            var button = $(this);

            var params = { 
                key: pixabay_key, 
                q: button.closest('tbody').find('tr:eq(0)').find('#query').val(), 
                image_type: button.closest('tbody').find('tr:eq(1)').find('#image_type').val(),
                category: button.closest('tbody').find('tr:eq(2)').find('#category').val(),
                order: button.closest('tbody').find('tr:eq(3)').find('#order').val(),
                per_page: 50
            };
            
            var parameter = $.param(params);

            axios.get('https://pixabay.com/api/?' + parameter)
            .then(function(response){
                console.log(response)
                $('.pixabay-results').empty();
                var images = response.data.hits;

                if(response.data.totalHits == 0 || response.data.total == 0){
                    $.notify({
                    icon: "error",
                        message: 'No image found. Try different search combinations',
                    },{
                        type: 'danger'
                    });
                }else{

                    html += '<h4>Select image to use</h4>';
                    html += '<select name="pixabayImage" id="pixabay-images">';
                    $.each(images, function(i, image){
                        html += '<option data-img-src="'+ image.previewURL +'" value="'+ image.largeImageURL +'">';
                        html += '</option>';
                    });

                    html += '</select>';

                    button.closest('.card').find('.pixabay-results').append(html);
                    button.closest('.card').find('.pixabay-results').find('select#pixabay-images').imagepicker();
                }
            })
            .catch(function(error){
                $.notify({
                    icon: "error",
                    message: error.msg,
                },{
                    type: 'danger'
                });
            })

        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
