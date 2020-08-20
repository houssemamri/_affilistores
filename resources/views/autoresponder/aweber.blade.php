@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('page_title')
{{ ucfirst($autoresponder) }} API Settings
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Update {{ ucfirst($autoresponder) }} API </strong></h4>
        <p class="card-category">Add / Update {{ ucfirst($autoresponder) }} API credentials</p>
    </div>
    <div class="card-body">
        <form action="{{ route('autoresponder.create', ['subdomain' => Session::get('subdomain'), 'autoresponder' => $autoresponder]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        @if($autoresponder == 'aweber')
                            <tr>
                                <th width="20%"><strong>Consumer Key <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter AWeber Consumer Key">info</i></strong></th>
                                <td>
                                    <input type="text" name="consumer_key" placeholder="Enter Consumer Key" value="{{ json_decode($settings->settings)->consumer_key }}" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <th width="20%"><strong>Consumer Secret <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter AWeber Consumer Secret">info</i></strong></th>
                                <td>
                                    <input type="text" name="consumer_secret" placeholder="Enter Consumer Secret" value="{{ json_decode($settings->settings)->consumer_secret }}" class="form-control">
                                </td>
                            </tr>
                            @if(isset($data->lists))
                            <tr>
                                <th width="20%"><strong>List <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Select your aweber list to add your subscribers.">info</i></strong></th>
                                <td>
                                    <select name="list" class="form-control">
                                        <option value="">Please select your AWeber list</option>
                                        @foreach($data->lists as $list)
                                        <option value="{{ $list->id }}" {{ $list->id == $data->list_id ? 'selected' : '' }}>{{ $list->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endif
                       
                        @endif
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>

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

