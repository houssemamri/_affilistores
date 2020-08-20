@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('page_title')
GetResponse API
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Update GetResponse API </strong></h4>
        <p class="card-category">Add / Update GetResponse API credentials</p>
    </div>
    <div class="card-body">
        <form action="{{ route('newsletters.getresponse', ['subdomain' => Session::get('subdomain')]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="20%"><strong>API Key <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter GetResponse API KEy">info</i></strong></th>
                            <td>
                                <input type="text" name="api_key" placeholder="Enter API Key" value="{{ json_decode($settings->settings)->api_key }}" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Campaign Name <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter campaign name to add your contacts">info</i></strong></th>
                            <td>
                                <input type="text" name="campaign_name" placeholder="Enter Campaign Name" value="{{ json_decode($settings->settings)->campaign_name }}" class="form-control">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>

            @if(isset(json_decode($settings->settings)->campaign_name) && json_decode($settings->settings)->api_key)
            <a href="{{ route('newsletters.getresponse.sync', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-warning">Sync Subcribers</a>
            @endif
            <a href="{{ route('newsletters.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
            
            <div class="clearfix"></div>
        </form>
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

