@extends('admin.master')

@section('page_title')
Polls
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Polls </strong></h4>
                <p class="card-category">Manage your polls</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('polls.add') }}" class="pull-right btn btn-warning">Create New Poll</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-center" id="poll-list">
                <thead class="">
                    <tr>
                        <th>Question</th>
                        <th>Results</th>
                        <th>Total Vote</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($polls as $poll)
                    <tr>
                        <td>{{ $poll->question }}</td>
                        <td width="40%">
                            <table width="100%">
                                <tbody>
                                    @foreach($poll->options as $option)
                                        <tr>
                                            <td>{{ $option->name }}</td>
                                            <td>{{ count($option->votes) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan=2>
                                                <div class="progress">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($poll->total_vote == 0) ? 0 : (count($option->votes) / $poll->total_vote) * 100 }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                    <small class="justify-content-center d-flex">{{ ($poll->total_vote == 0) ? 0 : number_format((count($option->votes) / $poll->total_vote) * 100, 2, '.', '') }}%</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td>{{ $poll->total_vote }}</td>
                        <td>{!! ($poll->status) ? "<span class='badge badge-pill badge-success'>Active</span>" : "<span class='badge badge-pill badge-dark'>Hidden</span>" !!}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-sm btn-default" href="{{ route('polls.edit', Crypt::encrypt($poll->id)) }}" onclick="">
                                    <i class="material-icons">create</i>
                                </a>
                                <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($poll->id) }}')">
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
    <form action="{{ route('polls.delete') }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this poll? </strong></h4>
                            <p class="card-category">Deleting poll will also remove related data</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="poll_id" id="poll_id">
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
            $('#poll-list').DataTable();
        } );

        function confirmDelete(poll_id) {
            $('#deleteConfirmation').modal();
            $('#poll_id').val(poll_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#poll_id').val('');
        });
    </script>

    
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
