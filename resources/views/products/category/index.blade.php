@extends('master')

@section('page_title')
Categories
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Categories <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Once you have created your categories below remember to create your menu and add all categories to it to ensure that they are all displayed in your store.">info</i></strong></h4>
                <p class="card-category">Manage your product categories</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('categories.create', ['subdomain' => Session::get('subdomain')]) }}" class="pull-right btn btn-warning">Create New Category</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-fixed text-center" id="category-list">
                <thead class="">
                    <tr>
                        <th>Category Name</th>
                        <th>Type</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td> Main Category </td>
                            <td>{{ date_format($category->created_at, 'm/d/Y') }}</td>
                            <td>{!! ($category->status) ? "<span class='badge badge-pill badge-success'>Active</span>" : "<span class='badge badge-pill badge-dark'>Inactive</span>" !!}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('categories.edit', ['id' => Crypt::encrypt($category->id), 'subdomain' => Session::get('subdomain') ]) }}" class="btn btn-default">
                                        <i class="material-icons">create</i>
                                    </a>
                                    <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($category->id) }}')" class="btn btn- btn-default">
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
    <form action="{{ route('categories.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this category? </strong></h4>
                            <p class="card-category">Deleting this category will remove related data</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="category_id" id="category_id">
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
            $('#category-list').DataTable();
        } );

        $('[data-toggle="tooltip"]').tooltip()

        function confirmDelete(category_id) {
            $('#deleteConfirmation').modal();
            $('#category_id').val(category_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#category_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
