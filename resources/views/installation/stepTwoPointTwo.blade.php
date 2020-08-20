@extends('installation.master')

@section('content')
<form action="{{  route('installation.stepTwoPointTwo') }}" method="POST" enctype="multipart/form-data">
    <div class="col-lg-12">
        <div class="row">
            {!! csrf_field() !!}
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Facebook Configurations  </strong></h4>
                        <p class="card-category">Enter Facebook API keys</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Get your Facebook API Keys here</th>
                                        <td><a href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a></td>
                                    </tr>
                                    <tr>
                                        <th>Facebook App ID</th>
                                        <td>
                                            <input type="text" name="facebook_app_id" class="form-control" value="{{ old('facebook_app_id') }}">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Facebook App Secret</th>
                                        <td>
                                            <input type="text" name="facebook_app_secret" class="form-control" value="{{ old('facebook_app_secret') }}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Twitter Configurations  </strong></h4>
                        <p class="card-category">Enter Twitter API keys</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Get your Twitter API Keys here</th>
                                        <td><a href="https://apps.twitter.com/" target="_blank">https://apps.twitter.com/</a></td>
                                    </tr>
                                    <tr>
                                        <th>Twitter Consumer Key</th>
                                        <td>
                                            <input type="text" name="twitter_consumer_key" class="form-control" value="{{ old('twitter_consumer_key') }}">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Twitter Consumer Secret</th>
                                        <td>
                                            <input type="text" name="twitter_consumer_secret" class="form-control" value="{{ old('twitter_consumer_secret') }}">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Twitter Access Token</th>
                                        <td>
                                            <input type="text" name="twitter_access_token" class="form-control" value="{{ old('twitter_access_token') }}">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Twitter Access Token Secret</th>
                                        <td>
                                            <input type="text" name="twitter_access_token_secret" class="form-control" value="{{ old('twitter_access_token_secret') }}">
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>

                        
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">Next</button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection