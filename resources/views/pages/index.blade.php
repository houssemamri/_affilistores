@extends('master')

@section('page_title')
{{ $page->title }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h1 class="text-uppercase"><strong>{{ $page->title }}</strong></h1>
                <hr>
                {!! html_entity_decode($page->body) !!}
            </div>
        </div>
    </div>    
</div>
@endsection


