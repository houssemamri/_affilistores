@extends('master')

@section('page_title')
Affiliate Settings
@endsection

@section('content')
@if(count($otherStores) > 0)
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Import All Settings </strong></h4>
        <p class="card-category">Import all affiliate settings from your other store</p>
    </div>
    <div class="card-body">
        <form action="{{ route('affiliate.import.all', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td width="15%">Select store to get affiliate settings</td>
                                    <td width="50%">
                                        <select name="store" class="form-control" required>
                                        <option value="">Please select store to get affiliate settings</option>
                                            @foreach($otherStores as $store)
                                            <option value="{{ Crypt::encrypt($store->id) }}">{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width="30%">
                                        <button type="submit" class="btn btn-default">Import All Settings</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<div class="card">
  <div class="card-header card-header-primary">
    <div class="nav-tabs-navigation">
      <div class="nav-tabs-wrapper">
        <ul class="nav nav-tabs" data-tabs="tabs">
            @if(in_array('amazon', $affiliates))
            <li class="nav-item">
                <a class="nav-link active show" href="#amazon" data-toggle="tab">
                    Amazon <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Enter Amazon affiliate keys. Don't have Amazon API Keys yet? Follow this link: https://docs.aws.amazon.com/AWSECommerceService/latest/DG/becomingDev.html">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endif
            @if(in_array('ebay', $affiliates))
            <li class="nav-item">
                <a class="nav-link" href="#ebay" data-toggle="tab">
                    Ebay <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Enter Ebay affiliate keys. Don't have Ebay API Keys yet? Register and Login to this link https://developer.ebay.com/signin">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endif
            @if(in_array('aliexpress', $affiliates))
            <li class="nav-item">
                <a class="nav-link" href="#aliexpress" data-toggle="tab">
                    AliExpress <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Enter AliExpress affiliate keys. Don't have AliExpress API Keys yet? Follow this link: https://epn.bz/en/affiliate/webmaster">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endif
            @if(in_array('walmart', $affiliates))
            <li class="nav-item">
                <a class="nav-link" href="#walmart" data-toggle="tab">
                    Walmart <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Enter Walmart API Key. Don't have Walmart API Key yet? Follow this link: https://developer.walmartlabs.com">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endif
            @if(in_array('shop.com', $affiliates))
            <li class="nav-item">
                <a class="nav-link" href="#shopcom" data-toggle="tab">
                    Shop.com <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Enter Shop.com API Key. Don't have Shop.com API Key yet? Follow this link: http://developer.shop.com/gettingstarted">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endif
            @if(in_array('cj.com', $affiliates))
            <li class="nav-item">
                <a class="nav-link" href="#cjcom" data-toggle="tab">
                    Cj.com <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Enter Cj.com API Key and Website ID. Don't have Cj.com API Key yet? Register here http://www.cj.com/publisher-sign, after registration follow this link https://cjcommunity.force.com/s/article/Getting-Started-with-Web-Services-4777164">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endif
            @if(in_array('jvzoo', $affiliates))
            <li class="nav-item">
                <a class="nav-link" href="#jvzoo" data-toggle="tab">
                    JVZoo <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Enter Affiliate ID to attached to the products once added.">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endif
            @if(in_array('clickbank', $affiliates))
            <li class="nav-item">
                <a class="nav-link" href="#clickbank" data-toggle="tab">
                    ClickBank <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Enter account name to generate affiliate link / hop link of the product you added.">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endif
            @if(in_array('warriorplus', $affiliates))
            <li class="nav-item">
                <a class="nav-link" href="#warriorplus" data-toggle="tab">
                    Warrior Plus <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endif
            @if(in_array('paydotcom', $affiliates))
            <li class="nav-item">
                <a class="nav-link" href="#paydotcom" data-toggle="tab">
                    PayDotCom <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Enter affiliate id to generate affiliate link of product you added.">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            @endif
        </ul>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane fade show active" id="amazon">
        <div class="row">
            <div class="col-lg-12">
                <h5><strong> Don't have Amazon API Keys yet? Follow this </strong> <a href="https://docs.aws.amazon.com/AWSECommerceService/latest/DG/becomingDev.html" target="_blank">link</a></h5>
                <hr>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Amazon Affiliate Settings </strong></h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('amazon', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-12">
                                    <br>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="useOwnKeys" type="checkbox" {{ isset( json_decode($affiliateSettings->where('name', 'amazon')->first()->settings)->use_own_keys) &&  json_decode($affiliateSettings->where('name', 'amazon')->first()->settings)->use_own_keys == 'false' ? 'checked' : ''}}>
                                            Check to use Affilistores.Net default credentials to search Amazon Products while waiting for your Secret Key but still use your credentials(Associate Tag & Access Key) to generate the affiliate link
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                    <label class="bmd-label-floating">Market Place *</label>
                                        <select name="market_place" id="market_place" class="form-control">
                                            @foreach($marketPlaces as $key => $marketPlace)
                                            <option value="{{ $marketPlace }}" {{ json_decode($affiliateSettings->where('name', 'amazon')->first()->settings)->market_place == $marketPlace ? 'selected' : ''}}>{{ $marketPlace }} ({{ $key }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Associate Id * <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Follow the instructions in this link https://docs.aws.amazon.com/AWSECommerceService/latest/DG/becomingAssociate.html to get your associate id">info</i></label>
                                    <input type="text" name="associate_tag" placeholder="Enter Associate Tag" value="{{ json_decode($affiliateSettings->where('name', 'amazon')->first()->settings)->associate_tag }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                    <label class="bmd-label-floating">Access Key * <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Follow the instructions in this link https://docs.aws.amazon.com/AWSECommerceService/latest/DG/becomingDev.html to get your Access Key">info</i></label>
                                    <input type="text" name="access_key" placeholder="Enter Access Key" value="{{ json_decode($affiliateSettings->where('name', 'amazon')->first()->settings)->access_key_id }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                    <label class="bmd-label-floating">Secret Key * <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Follow the instructions in this link https://docs.aws.amazon.com/AWSECommerceService/latest/DG/becomingDev.html to get your Secret Key">info</i></label>
                                    <input type="text" name="secret_key" placeholder="Enter Secret Key" value="{{ json_decode($affiliateSettings->where('name', 'amazon')->first()->settings)->secret_access_key }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Import Amazon Affiliate Settings </strong></h4>
                        <p class="card-category">Use this to import or copy Amazon affiliate settings to your other store</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('affiliate.import', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="amazon">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td width="15%">Select store to get amazon affiliate settings</td>
                                                    <td width="50%">
                                                        <select name="store" class="form-control" required>
                                                        <option value="">Please select store to get affiliate settings</option>
                                                            @foreach($otherStores as $store)
                                                            <option value="{{ Crypt::encrypt($store->id) }}">{{ $store->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td width="30%">
                                                        <button type="submit" class="btn btn-default">Import Settings</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="tab-pane fade" id="ebay">
        <h5><strong> Don't have Ebay API Keys yet? Register and login to this link </strong> <a href="https://developer.ebay.com/signin" target="_blank">link</a></h5>
        <hr>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Ebay Affiliate Settings </strong></h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ebay', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Application ID * <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Register and sign in here: https://developer.ebay.com/signin. Get from your delveoper ebay account, in the dashboard create keyset and get your production Access Key">info</i></label>
                                        <input type="text" name="application_id" placeholder="Enter Application ID" value="{{ json_decode($affiliateSettings->where('name', 'ebay')->first()->settings)->application_id }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Network ID</label>
                                        <input type="text" name="network_id" placeholder="Enter Network ID" value="{{ json_decode($affiliateSettings->where('name', 'ebay')->first()->settings)->network_id }}" class="form-control" >
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Tracking ID / Campaign ID <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Register and login here: https://epn.ebay.com/login. Get this from your epn ebay account. Campaigns > Active Campaigns">info</i></label>
                                        <input type="text" name="tracking_id" placeholder="Enter Tracking ID" value="{{ json_decode($affiliateSettings->where('name', 'ebay')->first()->settings)->tracking_id }}" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Custom ID</label>
                                        <input type="text" name="custom_id" placeholder="Enter Custom ID" value="{{ json_decode($affiliateSettings->where('name', 'ebay')->first()->settings)->custom_id }}" class="form-control" >
                                    </div>
                                </div>
                            </div> --}}

                            <button type="submit" class="btn btn-primary">Save</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Import Ebay Affiliate Settings </strong></h4>
                        <p class="card-category">Use this to import or copy Ebay affiliate settings to your other store</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('affiliate.import', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="ebay">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td width="15%">Select store to get ebay affiliate settings</td>
                                                    <td width="50%">
                                                        <select name="store" class="form-control" required>
                                                        <option value="">Please select store to get affiliate settings</option>
                                                            @foreach($otherStores as $store)
                                                            <option value="{{ Crypt::encrypt($store->id) }}">{{ $store->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td width="30%">
                                                        <button type="submit" class="btn btn-default">Import Settings</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
      </div>
      <div class="tab-pane fade" id="aliexpress">
        <h5><strong> Don't have AliExpress API Keys yet? Follow this </strong> <a href="https://epn.bz/en/affiliate/webmaster" target="_blank">link</a></h5>
        <hr>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> AliExpress Affiliate Settings </strong></h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('aliexpress', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">API Key * <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Upon registering in ePN follow this link https://epn.bz/en/cabinet#/epn-api and see this image {{ asset('img/tips/ApiKey.png') }} to get your API key">info</i></label>
                                        <input type="text" name="api_key" placeholder="Enter API Key" value="{{ json_decode($affiliateSettings->where('name', 'aliexpress')->first()->settings)->key }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">DeepLink Hash <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Upon registering in ePN follow this image instructions {{ asset('img/tips/Deeplink.png') }} to get your Deeplink Hash">info</i></label>
                                        <input type="text" name="deep_link_hash" placeholder="Enter DeepLink Hash" value="{{ json_decode($affiliateSettings->where('name', 'aliexpress')->first()->settings)->deep_link_hash }}" value="" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Import AliExpress Affiliate Settings </strong></h4>
                        <p class="card-category">Use this to import or copy AliExpress affiliate settings to your other store</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('affiliate.import', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="aliexpress">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td width="15%">Select store to get aliexpress affiliate settings</td>
                                                <td width="50%">
                                                    <select name="store" class="form-control" required>
                                                    <option value="">Please select store to get affiliate settings</option>
                                                        @foreach($otherStores as $store)
                                                        <option value="{{ Crypt::encrypt($store->id) }}">{{ $store->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td width="30%">
                                                    <button type="submit" class="btn btn-default">Import Settings</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="tab-pane fade" id="walmart">
        <h5><strong> Don't have Walmart API Key yet? Follow this </strong> <a href="https://developer.walmartlabs.com/" target="_blank">link</a></h5>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Walmart Affiliate Settings </strong></h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('walmart', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Key <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Upon registering or logging in follow this link https://developer.walmartlabs.com/apps/mykeys to create or get your key">info</i></label>
                                        <input type="text" name="key" placeholder="Enter API Key" value="{{ ($affiliateSettings->where('name', 'walmart')->first() !== null) ? json_decode($affiliateSettings->where('name', 'walmart')->first()->settings)->key : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Import Walmart Affiliate Settings </strong></h4>
                        <p class="card-category">Use this to import or copy Walmart affiliate settings to your other store</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('affiliate.import', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="walmart">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td width="15%">Select store to get Walmart affiliate settings</td>
                                                    <td width="50%">
                                                        <select name="store" class="form-control" required>
                                                        <option value="">Please select store to get affiliate settings</option>
                                                            @foreach($otherStores as $store)
                                                            <option value="{{ Crypt::encrypt($store->id) }}">{{ $store->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td width="30%">
                                                        <button type="submit" class="btn btn-default">Import Settings</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="tab-pane fade" id="shopcom">
        <h5><strong> Don't have Shop.com API Key yet? Follow this </strong> <a href="http://developer.shop.com/gettingstarted" target="_blank">link</a></h5>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Shop.com Affiliate Settings </strong></h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('shopcom', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">API Key <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Upon registering or logging in follow this image instructions {{ asset('img/tips/Shopcom-apikey.png') }}">info</i></label>
                                        <input type="text" name="api_key" placeholder="Enter API Key" value="{{ ($affiliateSettings->where('name', 'shopcom')->first() !== null) ? json_decode($affiliateSettings->where('name', 'shopcom')->first()->settings)->api_key : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Publisher ID <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Register to this link https://affiliate.shop.com/join/register to get your publisher id">info</i></label>
                                        <input type="text" name="publisher_id" placeholder="Enter Publisher ID" value="{{ ($affiliateSettings->where('name', 'shopcom')->first() !== null) ? json_decode($affiliateSettings->where('name', 'shopcom')->first()->settings)->publisher_id : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Import Shop.com Affiliate Settings </strong></h4>
                        <p class="card-category">Use this to import or copy Shop.com affiliate settings to your other store</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('affiliate.import', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="shopcom">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td width="15%">Select store to get Shop.com affiliate settings</td>
                                                    <td width="50%">
                                                        <select name="store" class="form-control" required>
                                                        <option value="">Please select store to get affiliate settings</option>
                                                            @foreach($otherStores as $store)
                                                            <option value="{{ Crypt::encrypt($store->id) }}">{{ $store->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td width="30%">
                                                        <button type="submit" class="btn btn-default">Import Settings</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="tab-pane fade" id="cjcom">
        <h5><strong> Don't have Cj.com API Key yet? Follow this </strong> <a href="https://cjcommunity.force.com/s/article/Getting-Started-with-Web-Services-4777164" target="_blank">link</a></h5>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Import CJ.com Affiliate Settings </strong></h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cjcom', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Website ID <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="After registration go to this link https://members.cj.com/member/4617237/accounts/publisher/sites.cj and follow this image guide to get your Website ID {{ asset('img/tips/Cjcom-websiteid.png') }}">info</i></label>
                                        <input type="text" name="website_id" placeholder="Enter Website ID" value="{{ ($affiliateSettings->where('name', 'cjcom')->first() !== null) ? json_decode($affiliateSettings->where('name', 'cjcom')->first()->settings)->website_id : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">API Key <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="After regsitration go to this link https://developers.cj.com/ and sign in on the bottom left corner. Then go to the Authentication > Personal Access Token then Register a New Personal Access Token enter any name you want then you will be given the API key or token created and save that and use that to enter you CJ.com affiliate settings.">info</i></label>
                                        <input type="text" name="api_key" placeholder="Enter API Key" value="{{ ($affiliateSettings->where('name', 'cjcom')->first() !== null) ? json_decode($affiliateSettings->where('name', 'cjcom')->first()->settings)->api_key : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        
                            <button type="submit" class="btn btn-primary">Save</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Import CJ.com Affiliate Settings </strong></h4>
                        <p class="card-category">Use this to import or copy CJ.com affiliate settings to your other store</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('affiliate.import', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="cjcom">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td width="15%">Select store to get Cj.com affiliate settings</td>
                                                    <td width="50%">
                                                        <select name="store" class="form-control" required>
                                                        <option value="">Please select store to get affiliate settings</option>
                                                            @foreach($otherStores as $store)
                                                            <option value="{{ Crypt::encrypt($store->id) }}">{{ $store->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td width="30%">
                                                        <button type="submit" class="btn btn-default">Import Settings</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="tab-pane fade" id="jvzoo">
          <div class="row">
                <div class="col-lg-6">
                  <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Import JVZoo Affiliate Settings </strong></h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('jvzoo', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                                {!! csrf_field() !!}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Affiliate ID <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="After registration and logging in go to My Account > My Account then go to Affiliate Information to get Affiliate ID">info</i></label>
                                            <input type="text" name="affiliate_id" placeholder="Enter Affiliate ID" value="{{ ($affiliateSettings->where('name', 'jvzoo')->first() !== null) ? json_decode($affiliateSettings->where('name', 'jvzoo')->first()->settings)->affiliate_id : '' }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Save</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                  </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Import JVZoo Affiliate Settings </strong></h4>
                            <p class="card-category">Use this to import or copy JVZoo affiliate settings to your other store</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('affiliate.import', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="jvzoo">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td width="15%">Select store to get JVZoo affiliate settings</td>
                                                        <td width="50%">
                                                            <select name="store" class="form-control" required>
                                                            <option value="">Please select store to get affiliate settings</option>
                                                                @foreach($otherStores as $store)
                                                                <option value="{{ Crypt::encrypt($store->id) }}">{{ $store->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td width="30%">
                                                            <button type="submit" class="btn btn-default">Import Settings</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
          </div>
      </div>
        <div class="tab-pane fade" id="clickbank">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> ClickBank Affiliate Settings </strong></h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('clickbank', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                                {!! csrf_field() !!}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Account Name <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Enter Account Nickname / Username">info</i></label>
                                            <input type="text" name="account_name" placeholder="Enter Account Name" value="{{ ($affiliateSettings->where('name', 'clickbank')->first() !== null) ? json_decode($affiliateSettings->where('name', 'clickbank')->first()->settings)->account_name : '' }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Save</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Import ClickBank Affiliate Settings </strong></h4>
                            <p class="card-category">Use this to import or copy ClickBank affiliate settings to your other store</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('affiliate.import', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="clickbank">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td width="15%">Select store to get ClickBank affiliate settings</td>
                                                        <td width="50%">
                                                            <select name="store" class="form-control" required>
                                                            <option value="">Please select store to get affiliate settings</option>
                                                                @foreach($otherStores as $store)
                                                                <option value="{{ Crypt::encrypt($store->id) }}">{{ $store->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td width="30%">
                                                            <button type="submit" class="btn btn-default">Import Settings</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="warriorplus">
            @if(isset($instructions['warriorplus']))
                {!! html_entity_decode($instructions['warriorplus']->instructions) !!}
            @endif
        </div>

        <div class="tab-pane fade" id="paydotcom">
        @if(isset($instructions['paydotcom']))
            {!! html_entity_decode($instructions['paydotcom']->instructions) !!}
        @endif   
        </div>
    </div>
  </div>
</div>
@endsection

@section('custom-scripts')
<script>
    // $('[data-toggle="tooltip"]').tooltip()
    $('[data-toggle="popover"]').popover()
</script>
@endsection

@section('alert')
  @include('extra.alerts')
@endsection