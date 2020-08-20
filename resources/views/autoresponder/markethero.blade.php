@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('page_title')
Market Hero API Settings
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Update Market Hero API </strong></h4>
        <p class="card-category">Add / Update Market Hero API credentials</p>
    </div>
    <div class="card-body">
        <form action="{{ route('autoresponder.create', ['subdomain' => Session::get('subdomain'), 'autoresponder' => $autoresponder]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        @if($autoresponder == 'markethero')
                            <tr>
                                <th width="20%"><strong>API Key <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter Market Hero API Key">info</i></strong></th>
                                <td>
                                    <input type="text" name="api_key" placeholder="Enter API Key" value="{{ json_decode($settings->settings)->api_key }}" class="form-control">
                                </td>
                            </tr>
                            @if(isset($data->tags))
                            <tr>
                                <th width="20%"><strong>Tags <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Select your Market Hero tag to add your leads.">info</i></strong></th>
                                <td>
                                    <select name="tag" class="form-control">
                                        <option value="">Please select your Market Hero tag</option>
                                        @foreach($data->tags as $tag)
                                        <option value="{{ $tag }}" {{ $tag == $data->tag_id ? 'selected' : '' }}>{{ $tag }}</option>
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

