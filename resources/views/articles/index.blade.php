@extends('master')

@section('page_title')
Articles
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-primary ">
                <h4 class="card-title"><strong> Articles </strong></h4>
                <p class="card-category">Read some articles</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-fixed">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Published Date</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                            <tr>
                                <td>{{ $article->title }}</td>
                                <td>{{ date_format($article->created_at, 'M/d/Y') }}</td>
                                <td><a class="btn btn-primary" href="{{ route('articles.read', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($article->id)]) }}">Read</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>
        </div>
    </div>    
</div>
@endsection


