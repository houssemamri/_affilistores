@extends('master')

@section('page_title')
Create Tag
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Create Tag </strong></h4>
        <p class="card-category">Add new tag for your products</p>
    </div>
    <div class="card-body">
        <form action="{{ route('tags.create', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="20%"><strong>Name</strong></th>
                            <td>
                                <input type="text" name="name" placeholder="Enter Tag Name" class="form-control">
                            </td>
                        </tr>
                       
                        <tr>
                            <th withd="20%">Status</th>
                            <td>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="status" checked>
                                    Set to active
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('tags.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
            
            <div class="clearfix"></div>
        </form>
    </div>
</div>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
