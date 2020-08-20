@extends('master')

@section('page_title')
Stores
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-primary ">
                <h4 class="card-title"><strong> Store </strong></h4>
                <p class="card-category">Import / Export Store Themes and Settings</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="category-list">
                        <thead class="">
                            <tr>
                                <th>Store Name</th>
                                <th>Domain</th>
                                <th>No. of Products</th>
                                <th>Date Created</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($stores as $store)
                            <tr>
                                <td>{{ $store->name }}</td>
                                <td> <a href="{{ route('index', $store->subdomain) }}">{{ route('index', $store->subdomain) }}</a> </td>
                                <td>{{ $store->products()->count() }}</td>
                                <td>{{ date_format(date_create($store->created_at), 'F d, Y h:i a') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a class="btn btn-sm btn-default" href="{{ route('clone.export', [Session::get('subdomain'), Crypt::encrypt($store->id)]) }}">Export</a>
                                        <a class="btn btn-sm btn-default" href="{{ route('clone.import', [Session::get('subdomain'), Crypt::encrypt($store->id)]) }}">Import</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection


