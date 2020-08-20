@extends('master')

@section('page_title')
Banner Ads Settings
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Banner Ads </strong></h4>
        <p class="card-category">Add your banner ad here</p>
    </div>
    <div class="card-body">
        <form action="{{  route('bannerAd', ['subdomain' => Session::get('subdomain')]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="20%"><strong>Choose type of Ad <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose the type of ad you want to display. Google AdSense and Image Upload available">info</i></strong></th>
                            <td>
                                <select class="form-control" name="banner_ad_type" id="banner_ad_type">
                                    @if(in_array('google adsense', $features))
                                    <option value="GoogleAdSense" {{ ($bannerAds->where('type', 'GoogleAdSense')->first()->selected == 1) ? 'selected' : '' }}>Google AdSense</option>
                                    @endif
                                    <option value="ImageUpload" {{ ($bannerAds->where('type', 'ImageUpload')->first()->selected == 1) ? 'selected' : '' }}>Image Upload</option>
                                </select>   
                            </td>
                        </tr>
                        @if(in_array('google adsense', $features))
                        <tr id="google-adsense">
                            <th width="20%"><strong>Google AdSense Code <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Paste code from Google AdSense here">info</i></strong></th>
                            <td>
                                <textarea name="adsense_code" rows="10" class="form-control">{!! json_decode($bannerAds->where('type', 'GoogleAdSense')->first()->content)->code !!}</textarea>
                            </td>
                        </tr>
                        @endif
                        <tr id="img-upload">
                            <th> </th>
                            <td>
                                @if(isset(json_decode($bannerAds->where('type', 'ImageUpload')->first()->content)->banner_image))
                                <img src="{{ asset('img/uploads/' . Session::get('subdomain') . '/bannerAd/' . json_decode($bannerAds->where('type', 'ImageUpload')->first()->content)->banner_image) }}" alt="" class="img-fluid" id="banner-img">
                                @endif
                            </td>
                        </tr>
                        <tr id="img-upload">
                            <th width="20%"><strong>Banner Image <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Upload banner image. Standard image size is 1140px X 142px">info</i></strong></th>
                            <td>
                                <input type="file" name="banner_image" accept="image/*" class="form-control-file" id="banner_image">
                                <p class="text-muted">Standard image size is 1140px X 142px</p>
                            </td>
                        </tr>
                        <tr id="img-upload">
                            <th width="20%"><strong>Banner Link <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set link of your banner">info</i></strong></th>
                            <td>
                                @if(isset(json_decode($bannerAds->where('type', 'ImageUpload')->first()->content)->banner_link))
                                <input type="url" name="banner_link" value="{{ json_decode($bannerAds->where('type', 'ImageUpload')->first()->content)->banner_link }}" placeholder="Enter Banner Link" class="form-control">
                                @else
                                <input type="url" name="banner_link" placeholder="Enter Banner Link" class="form-control" >
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong>Menu Banner </strong></h4>
        <p class="card-category">Add your menu banner here</p>
    </div>
    <div class="card-body">
        <form action="{{  route('bannerAdMenu', ['subdomain' => Session::get('subdomain')]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="20%"><strong>Choose type of Ad <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose the type of ad you want to display. Google AdSense and Image Upload available">info</i></strong></th>
                            <td>
                                <select class="form-control" name="menu_banner_ad_type" id="menu_banner_ad_type">
                                    @if(in_array('google adsense', $features))
                                    <option value="MenuBannerAdSense" {{ ($bannerAds->where('type', 'MenuBannerAdSense')->first()->selected == 1) ? 'selected' : '' }}>Google AdSense</option>
                                    @endif
                                    <option value="MenuBanner" {{ ($bannerAds->where('type', 'MenuBanner')->first()->selected == 1) ? 'selected' : '' }}>Banner Image</option>
                                </select>   
                            </td>
                        </tr>

                        @if(in_array('google adsense', $features))
                        <tr id="menu-banner-google-adsense">
                            <th width="20%"><strong>Google AdSense Code <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Paste code from Google AdSense here">info</i></strong></th>
                            <td>
                                <textarea name="adsense_code" rows="10" class="form-control">{!! json_decode($bannerAds->where('type', 'MenuBannerAdSense')->first()->content)->code !!}</textarea>
                            </td>
                        </tr>
                        @endif
                        <tr class="banner-img">
                            <th> </th>
                            <td>
                                @if(isset(json_decode($bannerAds->where('type', 'MenuBanner')->first()->content)->image) && json_decode($bannerAds->where('type', 'MenuBanner')->first()->content)->image !== '')
                                <img src="{{ asset('img/uploads/' . Session::get('subdomain') . '/bannerAd/' . json_decode($bannerAds->where('type', 'MenuBanner')->first()->content)->image) }}" alt="" class="img-fluid" id="banner-img">
                                @endif
                            </td>
                        </tr>
                        <tr class="banner-img">
                            <th width="20%"><strong>Menu Banner Image <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Upload banner image for your menu. Standard image size is 650px x 60px">info</i></strong></th>
                            <td>
                                <input type="file" name="menu_banner_image" accept="image/*" class="form-control-file" id="banner_image">
                                <p class="text-muted">Standard image size is 650px x 60px</p>
                            </td>
                        </tr>
                        <tr class="banner-img">
                            <th width="20%"><strong>Menu Banner Link <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set link of your banner menu">info</i></strong></th>
                            <td>
                                @if(isset(json_decode($bannerAds->where('type', 'MenuBanner')->first()->content)->link))
                                <input type="url" name="menu_link" value="{{ json_decode($bannerAds->where('type', 'MenuBanner')->first()->content)->link }}" placeholder="Enter Banner Link" class="form-control">
                                @else
                                <input type="url" name="menu_link" placeholder="Enter Banner Link" class="form-control" >
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Enable Menu Banner: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you want to display this banner on menu">info</i></th>
                            <th>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        @if($bannerAds->where('type', 'MenuBannerAdSense')->first()->selected == 1)
                                        <input class="form-check-input" name="selected" type="checkbox" {{ $bannerAds->where('type', 'MenuBannerAdSense')->first()->selected == 1 ? 'checked' : '' }}>
                                        @else
                                        <input class="form-check-input" name="selected" type="checkbox" {{ $bannerAds->where('type', 'MenuBanner')->first()->selected == 1 ? 'checked' : '' }}>
                                        @endif
                                        Check to enable this banner
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </th>
                        </tr>
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

        function showHideMenuBannerType(type){
            if(type == 'MenuBannerAdSense'){
                $('tr#menu-banner-google-adsense').show();
                $('tr.banner-img').hide();
            }else{
                $('tr#menu-banner-google-adsense').hide();
                $('tr.banner-img').show();
            }
        }

        function showHideBannerType(type){
            if(type == 'GoogleAdSense'){
                $('tr#google-adsense').show();
                $('tr#img-upload').hide();
            }else{
                $('tr#google-adsense').hide();
                $('tr#img-upload').show();
            }
        }

        var type = $('#banner_ad_type').find(':selected');
        showHideBannerType(type.val());

        var menubannertype = $('#menu_banner_ad_type').find(':selected');
        showHideMenuBannerType(menubannertype.val());

        $('#banner_ad_type').change(function(){
            var option = $(this).find(':selected');
            showHideBannerType(option.val());
        });

        $('#menu_banner_ad_type').change(function(){
            var option = $(this).find(':selected');
            showHideMenuBannerType(option.val());
        });

        $("#banner_image").change(function(){
            readURL(this, '#banner-img');
        });

        $('[data-toggle="tooltip"]').tooltip()
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
