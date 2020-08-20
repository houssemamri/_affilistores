@extends('admin.master')

@section('page_title')
Market Place Instructions
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Market Place Instructions </strong></h4>
                <p class="card-category">Manage your market place instructions</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('instructions.add') }}" class="pull-right btn btn-warning">Create New Intructions</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table member-table" id="category-list">
                <thead class="">
                    <tr>
                        <th>Market Place</th>
                        <th>Date Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($instructions as $instruction)
                    <tr>
                        <td>{{ $instruction->market_place }}</td>
                        <td>{{ date_format(date_create($instruction->created_at ), 'Y-m-d') }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-sm btn-default" href="{{ route('instructions.edit', Crypt::encrypt($instruction->id)) }}" onclick="">
                                    <i class="material-icons">edit</i>
                                </a>
                                <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($instruction->id) }}')">
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
    <form action="{{ route('instructions.delete') }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this instuction? </strong></h4>
                            <p class="card-category">Deleting instruction will no be available anymore.</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="instruction_id" id="instruction_id">
                                <button type="submit" class="btn btn-primary">Submit</button>
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
            $('#category-list').DataTable({
                // "bPaginate": false,
                // "searching": false
            });
        } );

        function confirmDelete(instruction_id) {
            $('#deleteConfirmation').modal();
            $('#instruction_id').val(instruction_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#instruction_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
