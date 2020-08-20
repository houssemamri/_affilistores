@extends('installation.master')

@section('content')
<div class="col-lg-8 col-md-12">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> API Keys Configurations  </strong></h4>
            <p class="card-category">Enter API keys for each configuration</p>
        </div>
        <div class="card-body">
            <form action="{{  route('installation.stepTwoPointOne') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>JVZOO IPN KEY</th>
                                <td>
                                    <input type="text" name="jvzoo_key" class="form-control" value="{{ old('jvzoo_key') }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Get your YouTube API Keys here</th>
                                <td><a href="https://developers.google.com/youtube/v3/getting-started" target="_blank">https://developers.google.com/youtube/v3/getting-started</a></td>
                            </tr>
                            <tr>
                                <th>YouTube API Key</th>
                                <td>
                                    <input type="text" name="youtube_api_key" class="form-control" value="{{ old('youtube_api_key') }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Get your reCAPTCHA API Keys here</th>
                                <td><a href="https://www.google.com/recaptcha/intro/v3beta.html" target="_blank">https://www.google.com/recaptcha/intro/v3beta.html</a></td>
                            </tr>
                            <tr>
                                <th>CAPTCHA SECRET</th>
                                <td>
                                    <input type="text" name="captcha_secret" class="form-control" value="{{ old('captcha_secret') }}">
                                </td>
                            </tr>

                            <tr>
                                <th>CAPTCHA SITE KEY</th>
                                <td>
                                    <input type="text" name="captcha_site_key" class="form-control" value="{{ old('captcha_site_key') }}">
                                </td>
                            </tr>
                           
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Next</button>
                <div class="clearfix"></div>
                
            </form>
        </div>
    </div>
</div>
@endsection