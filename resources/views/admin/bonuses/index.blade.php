@extends('admin.master')

@section('page_title')
Bonuses
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Bonuses </strong></h4>
                <p class="card-category">Manage your bonuses</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('bonuses.add') }}" class="pull-right btn btn-warning">Add New Bonus</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-fixed" id="bonus-list">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Available For</th>
                        <th>File Size</th>
                        <th>Date Added</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bonuses as $bonus)
                        <tr>
                            <td>{{ $bonus->name }}</td>
                            <td>
                                @foreach($bonus->available as $available)
                                <span class='badge badge-pill badge-info'>{{ $available->membership->title }}</span>
                                @endforeach
                            </td>
                            <td>{{ $bonus->size }}</td>
                            <td>{{ date_format($bonus->created_at, 'F d, Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a class="btn btn-sm btn-default" href="{{ route('bonuses.edit', Crypt::encrypt($bonus->id)) }}" onclick="">
                                        <i class="material-icons">create</i>
                                    </a>
                                    <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($bonus->id) }}')">
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
    <form action="{{ route('bonuses.delete') }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this bonus? </strong></h4>
                            <p class="card-category">Deleting this bonus will not be available anymore.</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="bonus_id" id="bonus_id">
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
            $('#bonus-list').DataTable();
        } );

        function confirmDelete(bonus_id) {
            $('#deleteConfirmation').modal();
            $('#bonus_id').val(bonus_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#bonus_id').val('');
        });
    </script>

@endsection

@section('alert')
    @include('extra.alerts')
@endsection
