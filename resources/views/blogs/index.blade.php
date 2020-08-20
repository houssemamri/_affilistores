@extends('master')

@section('page_title')
Blogs
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Blogs <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Manage your blogs here. Blogs will be displayed in home page of your store">info</i></strong></h4>
                <p class="card-category">Manage your blogs</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('blogs.create', Session::get('subdomain')) }}" class="pull-right btn btn-warning">Create New Blog</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <h5><b>RSS Feed:</b> <em>{{ route('index.blogs.rss', Session::get('subdomain')) }}</em></h5>

        <div class="table-responsive">
            <table class="table " id="blog-list">
                <thead class="">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Product Category</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($blogs as $blog)
                    <tr>
                        <td width="30%">{{ $blog->title }}</td>
                        <td>{{ $blog->category->title }}</td>
                        <td>{!! $blog->published == 0 ? '<span class="badge badge-default">Unpublished</span>' : '<span class="badge badge-success">Published</span>' !!}</td>
                        <td>{{ isset($blog->productCategory) ? $blog->productCategory->name : '' }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                @if($blog->published == 0)
                                <a class="btn btn-sm btn-default" href="{{ route('blogs.publish', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($blog->id) ]) }}" onclick="">
                                    Publish
                                </a>
                                @else
                                <a class="btn btn-sm btn-default" href="{{ route('index.blog.view', ['subdomain' => Session::get('subdomain'), 'slug' => $blog->slug ]) }}" target="_blank">
                                    View
                                </a>
                                <a class="btn btn-sm btn-default" href="{{ route('blogs.unpublish', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($blog->id) ]) }}" onclick="">
                                    Unpublish
                                </a>
                                @endif
                                
                                <a class="btn btn-sm btn-default" href="{{ route('blogs.edit', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($blog->id) ]) }}" onclick="">
                                    <i class="material-icons">create</i>
                                </a>
                                <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($blog->id) }}')">
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
    <form action="{{ route('blogs.delete', Session::get('subdomain')) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this blog? </strong></h4>
                            <p class="card-category">Deleting blog is can't be restored</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="blogId" id="blog_id">
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
        $('[data-toggle="tooltip"]').tooltip({ html: true })

        $(document).ready(function() {
            $('#blog-list').DataTable();
        } );

        function confirmDelete(blog_id) {
            $('#deleteConfirmation').modal();
            $('#blog_id').val(blog_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#blog_id').val('');
        });
    </script>

    
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
