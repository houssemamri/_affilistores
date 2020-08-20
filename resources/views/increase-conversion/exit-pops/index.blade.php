@extends('master')

@section('page_title')
Exit Pops
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Exit Pops </strong></h4>
                <p class="card-category">Manage your exit pops here</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('exitpops.create', ['subdomain' => Session::get('subdomain')]) }}" class="pull-right btn btn-warning">Create New Exit Pop</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-fixed text-center" id="pop-list">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exitPops as $pop)
                        <tr>
                            <td>{{ $pop->name }}</td>
                            <td>{!! ($pop->status) ? "<span class='badge badge-pill badge-success'>In use</span>" : "" !!}</td>
                            <td>{{ date_format($pop->created_at, 'm/d/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('exitpops.edit', ['id' => Crypt::encrypt($pop->id), 'subdomain' => Session::get('subdomain') ]) }}" class="btn btn-default">
                                        <i class="material-icons">create</i>
                                    </a>
                                    <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($pop->id) }}')" class="btn btn- btn-default">
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
    <form action="{{ route('exitpops.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this exit pop? </strong></h4>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="exitpop_id" id="exitpop_id">
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
            $('#pop-list').DataTable();
        } );

        function confirmDelete(exitpop_id) {
            $('#deleteConfirmation').modal();
            $('#exitpop_id').val(exitpop_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#exitpop_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
