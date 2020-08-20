@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('page_title')
Add Blog Category
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Add Blog Category </strong></h4>
        <p class="card-category">Add new category for your products</p>
    </div>
    <div class="card-body">
        <form action="{{ route('blogs.categories.create', ['subdomain' => Session::get('subdomain')]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="20%"><strong>Title <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your blog category's title">info</i></strong></th>
                            <td>
                                <input type="text" name="title" placeholder="Enter Category Title" value="{{ old('name') }}" class="form-control category-name">
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Description <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter your blog category's description ">info</i></strong></th>
                            <td>
                                <textarea name="description" class="form-control"  cols="1" rows="5">{{ old('description') }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('categories.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
            
            <div class="clearfix"></div>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{!! asset('js/tagify.min.js') !!}"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip()
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
