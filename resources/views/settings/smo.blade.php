@extends('master')

@section('page_title')
SMO Settings
@endsection

@section('content')

<div class="card">
  <div class="card-header card-header-primary">
    <div class="nav-tabs-navigation">
      <div class="nav-tabs-wrapper">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="nav-item">
                <a class="nav-link active show" href="#account_details" data-toggle="tab">
                    Social Pages URL Details <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your social media sites page links here. If leave blank icons will not be displayed">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#design_options" data-toggle="tab">
                    Design Options <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose social icons design to display on your store">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane fade show active" id="account_details">
        <form action="{{ route('smo', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
            {!! csrf_field() !!}

            @foreach($smoSettings as $smoSetting)
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="bmd-label-floating">{{ ucfirst($smoSetting->name) }} Page URL</label>
                            <input type="text" name="{{ $smoSetting->name }}_page_url" value="{{ $smoSetting->page_url }}" placeholder="Enter {{ ucfirst($smoSetting->name) }} Page URL" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group"></div>
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="{{ $smoSetting->name }}_display_option" type="checkbox" {{ ($smoSetting->display_options == 1) ? 'checked' : '' }}>
                                    Show {{ ucfirst($smoSetting->name) }} Icon For Sharing
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <button type="submit" class="btn btn-primary">Save</button>
            <div class="clearfix"></div>
        </form>
      </div>
      <div class="tab-pane fade" id="design_options">
        <form action="{{ route('designOption', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
            {!! csrf_field() !!}
            @foreach($design_options as $design_option)
            <fieldset class="form-group">
                <div class="form-check form-check-radio">
                    <label class="form-check-label social-check">
                        <input class="form-check-input" type="radio" name="design_option" value="{{ $design_option->id }}" id="exampleRadios1" {{ $design_option->id == $smoSetting->design_options ? 'checked' : '' }}>
                                
                        <span class="circle">
                            <span class="check"></span>
                        </span>
                    </label>
                    <img src="{{ asset($design_option->img_path .'/facebook-logo.png') }}" alt="" class="img-fluid">
                    <img src="{{ asset($design_option->img_path .'/twitter-logo.png') }}" alt="" class="img-fluid">
                    <img src="{{ asset($design_option->img_path .'/linkedin-logo.png') }}" alt="" class="img-fluid">
                    <img src="{{ asset($design_option->img_path .'/google-logo.png') }}" alt="" class="img-fluid">
                    <img src="{{ asset($design_option->img_path .'/pinterest-logo.png') }}" alt="" class="img-fluid">
                </div>
            </fieldset>
            @endforeach
            <button type="submit" class="btn btn-primary">Save</button>
            <div class="clearfix"></div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom-scripts')
<script>
    $('[data-toggle="tooltip"]').tooltip()
</script>
@endsection

@section('alert')
  @include('extra.alerts')
@endsection