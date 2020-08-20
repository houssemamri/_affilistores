@extends('admin.master')

@section('page_title')
Stores
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Store </strong></h4>
                <p class="card-category">List of stores</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="category-list">
                <thead class="">
                    <tr>
                        <th>Store Name</th>
                        <th>Domain</th>
                        <th>Owner</th>
                        <th class="text-center">Total No. of Products</th>
                        <th>Date Created</th>
                        <th class="text-center">Total Product Clicks</th>
                        <th class="text-center">Total Product Affiliate Clicks</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($stores->chunk(100) as $stores)
                    @foreach($stores as $store)
                        <tr>
                            <td>{{ $store->name }}</td>
                            <td> <a href="{{ route('index', $store->subdomain) }}">{{ route('index', $store->subdomain) }}</a> </td>
                            <td>{{ isset($store->user) ? $store->user->name : '' }}</td>
                            <td class="text-center">{{ $store->products_count }}</td>
                            <td>{{ date_format(date_create($store->created_at), 'F d, Y h:i a') }}</td>
                            <td class="text-center">{{ $store->page_hits_count }}</td>
                            <td class="text-center">{{ $store->affiliate_hits_count }}</td>
                            <td>
                                @if($store->status == 1)
                                <span class="badge badge-success">Open</span>
                                @elseif($store->status == 0)
                                <span class="badge badge-danger">Close</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    @if($store->status == 1)
                                    <a class="btn btn-sm btn-default" href="{{ route('admin.store.change.status', [Crypt::encrypt($store->id), 'close']) }}">Close</a>
                                    @elseif($store->status == 0)
                                    <a class="btn btn-sm btn-default" href="{{ route('admin.store.change.status', [Crypt::encrypt($store->id), 'open']) }}">Open</a>
                                    @endif

                                    @if($store->featured == 1)
                                    <a class="btn btn-sm btn-default" href="{{ route('admin.store.change.status', [Crypt::encrypt($store->id), 'unfeatured']) }}">Unfeature</a>
                                    @elseif($store->featured == 0)
                                    <a class="btn btn-sm btn-default" href="{{ route('admin.store.change.status', [Crypt::encrypt($store->id), 'featured']) }}">Feature</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                   @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

 <!-- Modal -->
 <div class="modal fade" id="confirmStatus" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('articles.delete') }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this article? </strong></h4>
                            <p class="card-category">Deleting article is can't be restored</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="article_id" id="article_id">
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
        } );zz

        function confirmDelete(article_id) {
            $('#deleteConfirmation').modal();
            $('#article_id').val(article_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#article_id').val('');
        });
    </script>

    
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
