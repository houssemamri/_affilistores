@extends('master')

@section('page_title')
Social Settings
@endsection

@section('content')

<div class="card">
  <div class="card-header card-header-primary">
    <div class="nav-tabs-navigation">
      <div class="nav-tabs-wrapper">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="nav-item">
                <a class="nav-link active show" href="#facebook" data-toggle="tab">
                    Facebook <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter Facebook API Keys. Don't have Facebook API Keys? Follow this link: https://developers.facebook.com/docs/pages/getting-started">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#twitter" data-toggle="tab">
                    Twitter <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter Twitter API Keys. Don't have Twitter API Keys? Follow this link: https://apps.twitter.com/">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#instagram" data-toggle="tab">
                    Instagram
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tumblr" data-toggle="tab">
                    Tumblr <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter Tumblr API Keys. Don't have Tumblr API Keys? Register your app here https://www.tumblr.com/oauth/apps. After you register your app go to this link https://api.tumblr.com/console/calls/user/info and click the Show Keys button on upper right corner">info</i>

                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#pinterest" data-toggle="tab">
                    Pinterest <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter Pinterest API Keys. Don't have Pinterest API Keys? Follow this link https://developers.pinterest.com/apps/ and login to your pinterest account.">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane fade show active" id="facebook">
        <h5><strong>Enter Facebook API Keys. Don't have Facebook API Keys? Follow this </strong> <a href="https://developers.facebook.com/docs/pages/getting-started">link</a></h5>
        <h5>Facebook callback URL: <b><em>{{ route('facebook.callback', Session::get('subdomain')) }}</em></b></h5>
        <form action="{{ route('facebook', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Application ID *</label>
                        <input type="text" name="application_id" value="{{ json_decode($socialSettings->where('name', 'facebook')->first()->settings)->application_id }}" placeholder="Enter Application ID" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Application Secret *</label>
                        <input type="text" name="application_secret" value="{{ json_decode($socialSettings->where('name', 'facebook')->first()->settings)->application_secret }}" placeholder="Enter Application Secret" value="" class="form-control" >
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <div class="clearfix"></div>
        </form>
      </div>
      <div class="tab-pane fade" id="twitter">
        <h5><strong>Don't have Twitter API Keys? Follow this</strong> <a href="https://apps.twitter.com/">link</a></h5>
        <form action="{{ route('twitter', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
          {!! csrf_field() !!}
          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Consumer Key (API Key) *</label>
                    <input type="text" name="consumer_key" value="{{ json_decode($socialSettings->where('name', 'twitter')->first()->settings)->consumer_key }}" placeholder="Enter Consumer Key (API Key)" class="form-control">
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                      <label class="bmd-label-floating">Consumer Secret (API Secret) *</label>
                    <input type="text" name="consumer_secret" value="{{ json_decode($socialSettings->where('name', 'twitter')->first()->settings)->consumer_secret }}" placeholder="Enter Consumer Secret (API Secret)" value="" class="form-control" >
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Access Token *</label>
                    <input type="text" name="access_token" value="{{ json_decode($socialSettings->where('name', 'twitter')->first()->settings)->access_token }}" placeholder="Enter Access Token" value="" class="form-control" >
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Access Token Secret *</label>
                    <input type="text" name="access_token_secret" value="{{ json_decode($socialSettings->where('name', 'twitter')->first()->settings)->access_token_secret }}" placeholder="Enter Access Token Secret" value="" class="form-control" >
                  </div>
              </div>
          </div>

          <button type="submit" class="btn btn-primary">Save</button>
          <div class="clearfix"></div>
        </form>
      </div>
      <div class="tab-pane fade" id="instagram">
        <form action="{{ route('instagram', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
          {!! csrf_field() !!}
          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Username</label>
                    <input type="text" name="username" value="{{ json_decode($socialSettings->where('name', 'instagram')->first()->settings)->username }}" placeholder="Enter Username" class="form-control">
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                      <label class="bmd-label-floating">Password</label>
                    <input type="password" name="password" value="{{ json_decode($socialSettings->where('name', 'instagram')->first()->settings)->password }}" placeholder="Enter Password" value="" class="form-control" >
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Client Id</label>
                    <input type="text" name="client_id" value="{{ json_decode($socialSettings->where('name', 'instagram')->first()->settings)->client_id }}" placeholder="Enter Client Id" value="" class="form-control" >
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Client Secret</label>
                    <input type="text" name="client_secret" value="{{ json_decode($socialSettings->where('name', 'instagram')->first()->settings)->client_secret }}" placeholder="Enter Client Secret" value="" class="form-control" >
                  </div>
              </div>
          </div>

          <button type="submit" class="btn btn-primary">Save</button>
          <div class="clearfix"></div>
        </form>
      </div>
      <div class="tab-pane fade" id="tumblr">
        <h5>Guide to get your Tumblr API Keys</h5>
        <ul>
            <li><b>Don't have Tumblr API Keys? Register your app </b> <a href="https://www.tumblr.com/oauth/apps">here</a></b></li>
            <li><b>After you register your app go to this </b> <a href="https://api.tumblr.com/console/calls/user/info">link</a> <b>and click the Show Keys button on upper right corner</b></b></li>
        </ul>

        <form action="{{ route('tumblr', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
          {!! csrf_field() !!}
          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Consumer Key</label>
                    <input type="text" name="consumer_key" value="{{ json_decode($socialSettings->where('name', 'tumblr')->first()->settings)->consumer_key }}" placeholder="Enter Consumer Key" class="form-control">
                  </div>
              </div>
          </div>

          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Consumer Secret</label>
                    <input type="text" name="consumer_secret" value="{{ json_decode($socialSettings->where('name', 'tumblr')->first()->settings)->consumer_secret }}" placeholder="Enter Consumer Secret" class="form-control">
                  </div>
              </div>
          </div>

          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">OAuth Token</label>
                    <input type="text" name="oauth_token" value="{{ json_decode($socialSettings->where('name', 'tumblr')->first()->settings)->oauth_token }}" placeholder="Enter OAuth Token" class="form-control">
                  </div>
              </div>
          </div>

          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">OAuth Secret</label>
                    <input type="text" name="oauth_secret" value="{{ json_decode($socialSettings->where('name', 'tumblr')->first()->settings)->oauth_secret }}" placeholder="Enter OAuth Secret" class="form-control">
                  </div>
              </div>
          </div>

          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Blog Name</label>
                    <input type="text" name="blog_name" value="{{ json_decode($socialSettings->where('name', 'tumblr')->first()->settings)->blog_name }}" placeholder="Enter Blog Name" class="form-control">
                  </div>
              </div>
          </div>
          
          <button type="submit" class="btn btn-primary">Save</button>
          <div class="clearfix"></div>
        </form>
      </div>
      <div class="tab-pane fade" id="pinterest">
        <h5>Guide to get your Appliction Id and Application Secret</h5>
        <ul>
            <li><b>Don't have Pinterest API Keys? Follow this </strong> <a href="https://developers.pinterest.com/apps/">link</a> and login to your pinterest account.</b></li>
            <li><b>After logging in create a Pinterest App. After creating Pinterest App you will have your App Id and Application Secret</b></li>
            <li><b>You must copy this callback URL <em><b>{{ route('pinterest.callback', Session::get('subdomain')) }}</b></em> and paste in under "Redirect URIs" to get your access token.</b></li>
        </ul>
        
        <form action="{{ route('pinterest', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Application ID *</label>
                        <input type="text" name="application_id" value="{{ json_decode($socialSettings->where('name', 'pinterest')->first()->settings)->application_id }}" placeholder="Enter Application ID" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Application Secret *</label>
                        <input type="text" name="application_secret" value="{{ json_decode($socialSettings->where('name', 'pinterest')->first()->settings)->application_secret }}" placeholder="Enter Application Secret" value="" class="form-control" >
                    </div>
                </div>
            </div>

             <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="bmd-label-floating">Board Name*</label>
                        <input type="text" name="board_name" value="{{ json_decode($socialSettings->where('name', 'pinterest')->first()->settings)->board_name }}" placeholder="Enter Board Name" value="" class="form-control" >
                    </div>
                </div>
            </div>

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