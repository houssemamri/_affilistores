@extends('master')

@section('page_title')
Blog Feeds
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Blog Feeds <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Manage your blog feeds to get blogs from feed url your provide.<br> <i class='material-icons'>cached</i> - Get latest feed from the RSS Feed URL">info</i></strong></h4>
                <p class="card-category">Manage your blog feeds</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('blogs.feeds.create', Session::get('subdomain')) }}" class="pull-right btn btn-warning">Add New Feed</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="feed-list">
                <thead class="">
                    <tr>
                        <th>URL</th>
                        <th>Category</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($feeds as $feed)
                    <tr>
                        <td>{{ $feed->url }}</td>
                        <td>{{ $feed->category->title }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                            <a class="btn btn-sm btn-default" href="{{ route('blogs.feeds.update', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($feed->id) ]) }}" onclick="">
                                    <i class="material-icons">cached</i>
                                </a>
                                <a class="btn btn-sm btn-default" href="{{ route('blogs.feeds.edit', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($feed->id) ]) }}" onclick="">
                                    <i class="material-icons">create</i>
                                </a>
                                <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($feed->id) }}')">
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
    <form action="{{ route('blogs.feeds.delete', Session::get('subdomain')) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this feed? </strong></h4>
                            <p class="card-category">Deleting feed is can't be restored</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="feedId" id="feed_id">
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
            $('#feed-list').DataTable();
        } );

        function confirmDelete(feed_id) {
            $('#deleteConfirmation').modal();
            $('#feed_id').val(feed_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#feed_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
