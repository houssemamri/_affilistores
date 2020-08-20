@extends('master')

@section('page_title')
Edit Tag
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Edit Tag </strong></h4>
        <p class="card-category">Update tag for your products</p>
    </div>
    <div class="card-body">
        <form action="{{ route('tags.edit', ['id' => $id, 'subdomain' => Session::get('subdomain')]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="20%"><strong>Name</strong></th>
                            <td>
                                <input type="text" name="name" placeholder="Enter Tag Name" value="{{ $tag->name }}" class="form-control">
                            </td>
                        </tr>
                       
                        <tr>
                            <th withd="20%">Status</th>
                            <td>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="status" {{ ($tag->status) ? 'checked' : '' }}>
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
