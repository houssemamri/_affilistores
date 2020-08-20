@extends('master')

@section('page_title')
Subscribers
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Subscribers </strong></h4>
                <p class="card-category">Manage your subscribers</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('newsletters.index', ['subdomain' => Session::get('subdomain')]) }}" class="pull-right btn btn-warning">Back To Newsletter</a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-fixed" id="category-list">
                <thead class="">
                    <tr>
                        <th>Email</th>
                        <th>Date Subscribed</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscribers as $subscriber)
                        <tr>
                            <td>{{ $subscriber->email }}</td>
                            <td>{{ date_format($subscriber->created_at, 'm/d/Y') }}</td>
                            <td>
                                <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($subscriber->id) }}')" class="btn btn- btn-default">
                                    <i class="material-icons">delete</i>
                                </a>
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
    <form action="{{ route('newsletters.subscribers.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this subscriber? </strong></h4>
                            <p class="card-category">Deleting this subscriber is not restorable.</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="subscriber_id" id="subscriber_id">
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

        function confirmDelete(subscriber_id) {
            $('#deleteConfirmation').modal();
            $('#subscriber_id').val(subscriber_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#subscriber_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
