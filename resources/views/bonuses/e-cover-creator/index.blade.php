@extends('master')

@section('page_title')
    {{ $name }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-primary ">
                <div class="row">
                    <div class="col-lg-10 col-sm-12">
                        <h4 class="card-title"><strong> {{ $name }} </strong></h4>
                    </div>
                    <div class="col-lg-2 col-sm-12 pull-right">
                        <a href="{{ route('bonus.index', $store->subdomain) }}" class="btn btn-info btn-xs">Go Back to Bonuses</a>
                    </div>
                </div>
            </div>
            <div class="card-body text-center">
                <div class="embed-responsive embed-responsive-16by9">
                    <embed src="{{ asset('ecover/flat'.$id.'.swf') }}" type="application/x-shockwave-flash" >
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom-scripts')

@endsection