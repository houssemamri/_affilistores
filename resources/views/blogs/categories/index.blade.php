@extends('master')

@section('page_title')
Blog Categories
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong>Blog Categories </strong></h4>
                <p class="card-category">Manage your blog categories</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('blogs.categories.create', ['subdomain' => Session::get('subdomain')]) }}" class="pull-right btn btn-warning">Create New Blog Category</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-fixed text-center" id="category-list">
                <thead class="">
                    <tr>
                        <th>Category Title</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->title }}</td>
                            <td>{{ $category->description }}</td>
                            <td>{{ date_format($category->created_at, 'm/d/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('blogs.categories.edit', ['id' => Crypt::encrypt($category->id), 'subdomain' => Session::get('subdomain') ]) }}" class="btn btn-sm btn-default">
                                        <i class="material-icons">create</i>
                                    </a>
                                    <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($category->id) }}')" class="btn btn-sm btn-default">
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
    <form action="{{ route('blogs.categories.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this category? </strong></h4>
                            <p class="card-category">Deleting this category will remove related blogs and feeds</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="categoryId" id="category_id">
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
