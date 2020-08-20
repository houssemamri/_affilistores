@extends('master')

@section('page_title')
Tags
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Tags </strong></h4>
                <p class="card-category">Manage your product tags</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('tags.create', ['subdomain' => Session::get('subdomain')]) }}" class="pull-right btn btn-warning">Create New Tag</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table member-table" id="tags-list">
                <thead class="">
                    <tr>
                        <th>Tag Name</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tags as $tag)
                        <tr>
                            <td>{{ $tag->name }}</td>
                            <td>{{ date_format($tag->created_at, 'm/d/Y') }}</td>
                            <td>{!! ($tag->status) ? "<span class='badge badge-pill badge-success'>Active</span>" : "<span class='badge badge-pill badge-dark'>Inactive</span>" !!}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('tags.edit', ['id' => Crypt::encrypt($tag->id), 'subdomain' => Session::get('subdomain') ]) }}" class="btn btn-default">
                                        <i class="material-icons">create</i>
                                    </a>
                                    <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($tag->id) }}')" class="btn btn- btn-default">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach 
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('tags.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this tag? </strong></h4>
                            <p class="card-category">Deleting this tag will remove related data</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="tag_id" id="tag_id">
                                <button type="submit" class="btn btn-primary">Yes</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#tags-list').DataTable();
        } );

        function confirmDelete(tag_id) {
            $('#deleteConfirmation').modal();
            $('#tag_id').val(tag_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#tag_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
