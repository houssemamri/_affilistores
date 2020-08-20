@extends('master')

@section('page_title')
Legal Pages
@endsection

@section('content')
<div class="card">
  <div class="card-header card-header-primary">
    <div class="nav-tabs-navigation">
      <div class="nav-tabs-wrapper">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="nav-item">
                <a class="nav-link active show" href="#terms" data-toggle="tab">
                    Terms & Conditions <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Setup your Terms and Conditions of your store. Template is already setup, edit it accordingly.">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#privacy" data-toggle="tab">
                    Privacy Policy <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Setup your Privacy Policy of your store. Template is already setup, edit it accordingly.">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#contact_us" data-toggle="tab">
                    Contact Us <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter Contact Us message to display on contact page">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#gdpr" data-toggle="tab">
                    GDPR Compliance<i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter GDPR EU Compliance statement">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#affiliate_disclosure" data-toggle="tab">
                    Affiliate Disclosure <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter Affiliate Disclosure statement">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#cookie_policy" data-toggle="tab">
                    Cookie Policy <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter Cookie Policy statement">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade show active" id="terms">
            <form action="{{ route('legalPages', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                        <label class="bmd-label-floating">Terms & Conditions <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Update words inside '{ }' ">info</i></label>

                            <div class="form-group">
                                <textarea class="form-control editor terms" name="terms_and_conditions" id="editor-terms">{!! $legalPage->terms_conditions !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" name="btn_terms">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade" id="privacy">
            <form action="{{ route('legalPages', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
            {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="form-group">
                                <label class="bmd-label-floating">Privacy Policy <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Update words inside '{ }' ">info</i></label>
                                <textarea class="form-control editor" name="privacy_policy"  id="editor-privacy">{!! $legalPage->privacy_policy !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" name="btn_privacy">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade" id="contact_us">
            <form action="{{ route('legalPages', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="form-group">
                                <label class="bmd-label-floating">Contact Us</label>
                                <textarea class="form-control editor" name="contact_us" id="editor-contact">{!! $legalPage->contact_us !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" name="btn_contact_us">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade" id="gdpr">
            <form action="{{ route('legalPages', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="form-group">
                                <label class="bmd-label-floating">GDPR EU Compliance</label>
                                <textarea class="form-control editor" name="gdpr_compliance" id="editor-contact">{!! $legalPage->gdpr_compliance !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" name="btn_gdpr">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade" id="affiliate_disclosure">
            <form action="{{ route('legalPages', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="form-group">
                                <label class="bmd-label-floating">AFfiliate Disclosure</label>
                                <textarea class="form-control editor" name="affiliate_disclosure" id="editor-contact">{!! $legalPage->affiliate_disclosure !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" name="btn_affiliate_disclosure">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="tab-pane fade" id="cookie_policy">
            <form action="{{ route('legalPages', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="form-group">
                                <label class="bmd-label-floating">Cookie Policy</label>
                                <textarea class="form-control editor" name="cookie_policy" id="editor-contact">{!! $legalPage->cookie_policy !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" name="btn_cookie_policy">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
  </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>
    
    <script>
        // CKEDITOR.replace('editor-terms');
        // CKEDITOR.replace('editor-privacy');
        // CKEDITOR.replace('editor-contact');

        $('[data-toggle="tooltip"]').tooltip()
    </script>

    <script>
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
