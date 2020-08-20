@extends('master')

@section('page_title')
{{ $article->title }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h1 class="text-uppercase"><strong>{{ $article->title }}</strong></h1>
                <hr>
                {!! html_entity_decode(htmlspecialchars_decode($article->body)) !!}
            </div>
        </div>
        <a href="{{ route('articles.list', Session::get('subdomain')) }}" class="btn btn-primary">Go back to Articles</a>
    </div>    
</div>
@endsection


