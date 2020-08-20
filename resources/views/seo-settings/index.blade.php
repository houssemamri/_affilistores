@extends('master')

@section('page_title')
SEO Settings
@endsection

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('content')
<div class="card">
  <div class="card-header card-header-primary">
    <div class="nav-tabs-navigation">
      <div class="nav-tabs-wrapper">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="nav-item">
                <a class="nav-link {{ !Session::has('seo_selected') ? 'active show' : '' }}" href="#homepage" data-toggle="tab">
                    Homepage <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add SEO Settings to your home page">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ (Session::has('seo_selected') && Session::get('seo_selected') == 'productpage') ? 'active show' : '' }}" href="#productpage" data-toggle="tab">
                    Product Page <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose your Product's Meta Title format">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Session::has('seo_selected') && Session::get('seo_selected') == 'archivepage' ? 'active show' : '' }}" href="#archivepage" data-toggle="tab">
                    Archive Page <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose your Categories' Meta Title format">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Session::has('seo_selected') && Session::get('seo_selected') == 'titlesettings' ? 'active show' : '' }}" href="#titlesettings" data-toggle="tab">
                    Title Settings <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose your Search Title Page format">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#xmlsitemap" data-toggle="tab">
                    XML Sitemap <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Download or Visit your XML Sitemap. This can be used on Webmaster Verifications">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#rssfeed" data-toggle="tab">
                    RSS Feed <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Subscribe to RSS Feeds of your products">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Session::has('seo_selected') && Session::get('seo_selected') == 'webmasterssettings' ? 'active show' : '' }}" href="#webmasterssettings" data-toggle="tab">
                    Webmasters Settings <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Webmaster Verification is the process of proving that you own the site or app that you claim to own.">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Session::has('seo_selected') && Session::get('seo_selected') == 'analytics' ? 'active show' : '' }}" href="#analytics" data-toggle="tab">
                    Analytics <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add Analytics Code to track vistors actions to your site.">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade {{ !Session::has('seo_selected') ? 'active show' : '' }}" id="homepage">
            <form action="" method="POST">
                {!! csrf_field() !!}
                @include('seo-settings.includes.homepage')
                
                <button type="submit" name="btn_homepage" class="btn btn-primary">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade {{ (Session::has('seo_selected') && Session::get('seo_selected') == 'productpage') ? 'active show' : '' }}" id="productpage">
            <form action="" method="POST">
                {!! csrf_field() !!}
                @include('seo-settings.includes.productpage')

                <button type="submit" name="btn_productpage"  class="btn btn-primary">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade {{ (Session::has('seo_selected') && Session::get('seo_selected') == 'archivepage') ? 'active show' : '' }}" id="archivepage">
            <form action="" method="POST">
                {!! csrf_field() !!}
                @include('seo-settings.includes.archivepage')

                <button type="submit" name="btn_archivepage" class="btn btn-primary">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade {{ (Session::has('seo_selected') && Session::get('seo_selected') == 'titlesettings') ? 'active show' : '' }}" id="titlesettings">
            <form action="" method="POST">
                {!! csrf_field() !!}
                @include('seo-settings.includes.titlesettings')
                <button type="submit" name="btn_titlesettings" class="btn btn-primary">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade" id="xmlsitemap">
            <form action="" method="POST">
                {!! csrf_field() !!}
                @include('seo-settings.includes.xmlsitemap')

                <button type="submit" class="btn btn-primary">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade" id="rssfeed">
            <form action="" method="POST">
                {!! csrf_field() !!}
                @include('seo-settings.includes.rssfeed')

                <button type="submit" class="btn btn-primary">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade {{ (Session::has('seo_selected') && Session::get('seo_selected') == 'webmasterssettings') ? 'active show' : '' }}" id="webmasterssettings">
            <form action="" method="POST">
                {!! csrf_field() !!}
                @include('seo-settings.includes.webmasterssettings')

                <button type="submit" name="btn_webmasterssettings" class="btn btn-primary">Save</button>
                <div class="clearfix"></div>
            </form> 
        </div>
        <div class="tab-pane fade {{ (Session::has('seo_selected') && Session::get('seo_selected') == 'analytics') ? 'active show' : '' }}" id="analytics">
            <form action="" method="POST">
                {!! csrf_field() !!}
                @include('seo-settings.includes.analytics')

                <button type="submit" name="btn_analytics" class="btn btn-primary">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
  </div>
</div>
@endsection

@section('custom-scripts')
<script src="{!! asset('js/tagify.min.js') !!}"></script>
<script>
    var tags = $('[name=meta_keywords]').tagify({
        duplicate: false,
        hasMaxTags: true,
        maxTags: 8,
    });

    $(window).on('load', function(){
        var keywords = $('input[name=meta_keywords]').val();
        var length = $('tag').length;

        if(length >= 8){
            $('span.input').attr('data-placeholder', 'No more keywords allowed');
            $('span.input').removeAttr('contenteditable');
        }else{
            $('span.input').attr('data-placeholder', 'Enter Meta Keywords');
            $('span.input').attr('contenteditable', '');
        }

        $('tags').bind("DOMSubtreeModified",function(){
            var length = $('tag').length;

            if(length >= 8){
                $('span.input').attr('data-placeholder', 'No more keywords allowed');
                $('span.input').removeAttr('contenteditable');
                $('span.input').addClass('placeholder');
            }else{
                $('span.input').attr('data-placeholder', 'Enter Meta Keywords');
                $('span.input').attr('contenteditable', '');
            }
        });

    })
</script>
<script>
    function seoAnalysisMetaTitle(element, progressBar, li, metaTitle, score){
        var analysis = [];
        element.val(element.val().replace(/\s{2,}/g,' '));
        
        metaTitle.text(element.val());

        if(element.val().length == 0){
            progressBar.removeClass("bg-success");
            progressBar.removeClass("bg-danger");
            progressBar.removeClass("bg-warning"); 
            progressBar.addClass("bg-danger"); 
            progressBar.css("width", (element.val().length / 70 * 100) + 6 + '%')
            progressBar.text('Bad');
            score.css("background-color", '#f44336');
            li.find('span.pre-text').text('Meta title text contains '+ element.val().length +' character.');
            li.find('span.text').text('No meta title has been specified')
            
        }
        else if(element.val().length > 0 && element.val().length < 47){
            progressBar.removeClass("bg-success");
            progressBar.removeClass("bg-danger");
            progressBar.removeClass("bg-warning"); 
            progressBar.addClass("bg-warning"); 
            progressBar.css("width", (element.val().length / 70 * 100) + 6 + '%')
            progressBar.text('Low');
            score.css("background-color", '#ee7c1b');
            li.find('span.pre-text').text('Meta title text contains '+ element.val().length +' character.');
            li.find('span.text').text('This is far too low and should be increased text up to 60 characters')
        }
        else if(element.val().length >= 47 && element.val().length <= 60){
            progressBar.removeClass("bg-success");
            progressBar.removeClass("bg-danger");
            progressBar.removeClass("bg-warning"); 
            progressBar.addClass("bg-success"); 
            progressBar.css("width", (element.val().length / 70 * 100) + '%')
            progressBar.text('Good');
            score.css("background-color", '#4caf50');
            li.find('span.pre-text').text('Meta title text contains '+ element.val().length +' character.');
            li.find('span.text').text('This is good for SEO')
        }
        else if(element.val().length > 60){
            progressBar.removeClass("bg-success");
            progressBar.removeClass("bg-danger");
            progressBar.removeClass("bg-warning"); 
            progressBar.addClass("bg-warning"); 
            progressBar.css("width", (element.val().length / 70 * 100) + '%')
            progressBar.text('Too much');
            score.css("background-color", '#ee7c1b');
            li.find('span.pre-text').text('Meta title text contains '+ element.val().length +' character.');
            li.find('span.text').text('The SEO title is wider than the viewable limit.')
        }
    }
    
    function seoAnalysisMetaDescription(element, progressBar, li, mataDescription, score){
        var analysis = [];
        element.val(element.val().replace(/\s{2,}/g,' '));
        
        mataDescription.text(element.val());

        if(element.val().length == 0){
            progressBar.removeClass("bg-success");
            progressBar.removeClass("bg-danger");
            progressBar.removeClass("bg-warning"); 
            progressBar.addClass("bg-danger"); 
            progressBar.css("width", (element.val().length / 70 * 100) + 6 + '%')
            progressBar.text('Bad');
            score.css("background-color", '#f44336');
            li.find('span.pre-text').text('Meta title text contains '+ element.val().length +' character.');
            li.find('span.text').text('No meta title has been specified')
            
        }
        else if(element.val().length > 0 && element.val().length < 120){
            progressBar.removeClass("bg-success");
            progressBar.removeClass("bg-danger");
            progressBar.removeClass("bg-warning"); 
            progressBar.addClass("bg-warning"); 
            progressBar.css("width", (element.val().length / 70 * 100) + 6 + '%')
            progressBar.text('Low');
            score.css("background-color", '#ee7c1b');
            li.find('span.pre-text').text('Meta title text contains '+ element.val().length +' character.');
            li.find('span.text').text('This is far too low and should be increased text up to 160 characters')
        }
        else if(element.val().length >= 120 && element.val().length <= 160){
            progressBar.removeClass("bg-success");
            progressBar.removeClass("bg-danger");
            progressBar.removeClass("bg-warning"); 
            progressBar.addClass("bg-success"); 
            progressBar.css("width", (element.val().length / 70 * 100) + '%')
            progressBar.text('Good');
            score.css("background-color", '#4caf50');
            li.find('span.pre-text').text('Meta title text contains '+ element.val().length +' character.');
            li.find('span.text').text('This is good for SEO')
        }
        else if(element.val().length > 160){
            progressBar.removeClass("bg-success");
            progressBar.removeClass("bg-danger");
            progressBar.removeClass("bg-warning"); 
            progressBar.addClass("bg-warning"); 
            progressBar.css("width", (element.val().length / 70 * 100) + '%')
            progressBar.text('Too much');
            score.css("background-color", '#ee7c1b');
            li.find('span.pre-text').text('Meta title text contains '+ element.val().length +' character.');
            li.find('span.text').text('The meta description exceed the character limit of 160.')
        }
    }

    function seoAnalysisMetaKeywords(element, li, score){
        var taglength = $(element).find('tag').length;

        li.find('span.pre-text').text('Meta Keywords contains '+ taglength +' keywords. Maximum 8 keyword accepted');

        if(taglength == 0){
            score.css("background-color", '#f44336');
        }else if(taglength > 0 && taglength < 6){
            score.css("background-color", '#ee7c1b');
        }else if(taglength > 5)
            score.css("background-color", '#4caf50');
    }

    $('[data-toggle="tooltip"]').tooltip()

    $('input[name="options"]').change(function(){
        var option = $(this).prop('checked', 'checked');
        $('input[name="meta_title"]').val(option.val());
    })

    $('input[name="archive_options"]').change(function(){
        var option = $(this).prop('checked', 'checked');
        $('input[name="archive_meta_title"]').val(option.val());
    })

    $('input[name="titlesettings_options"]').change(function(){
        var option = $(this).prop('checked', 'checked');
        $('input[name="search_page_title"]').val(option.val());
    })

    $('.hompage-meta-title input[name="meta_title"]').on('keyup change', function(){
        seoAnalysisMetaTitle($(this), $('.hompage-meta-title .progress-bar'), $('.homepage-seo .meta-title-length'), $('.homepage-seo .meta-title-text'), $('.homepage-seo .meta-title-length .seo-score-icon'))
    })

    $('.hompage-meta-description textarea[name="meta_description"]').on('keyup change', function(){
        seoAnalysisMetaDescription($(this), $('.hompage-meta-description .progress-bar'), $('.homepage-seo .meta-description-length'), $('.homepage-seo .meta-description-text'), $('.homepage-seo .meta-description-length .seo-score-icon'))
    })
   
    $('.hompage-meta-keywords tags').on('keyup', function(){
        seoAnalysisMetaKeywords($(this), $('.homepage-seo .meta-keyword-length'), $('.homepage-seo .meta-keyword-length .seo-score-icon'));
    });
    
</script>
@endsection

@section('alert')
  @include('extra.alerts')
@endsection