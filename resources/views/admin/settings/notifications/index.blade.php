@extends('admin.master')

@section('page_title')
Notifications
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Notifications </strong></h4>
                <p class="card-category">Manage your notifications</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('settings.notification.add') }}" class="pull-right btn btn-warning">Create New Notication</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="notification-list">
                <thead class="text-center">
                    <tr>
                        <th>Subject</th>
                        <th>Content</th>
                        <th>Views</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($notifications as $notification)
                    <tr>
                        <td class="text-center">{{ $notification->subject }}</td>
                        <td width="60%">{!! $notification->body !!}</td>
                        <td class="text-center">{{ count($notification->views) }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-sm btn-default" href="{{ route('settings.notification.edit', Crypt::encrypt($notification->id)) }}" onclick="">
                                    <i class="material-icons">create</i>
                                </a>
                                <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($notification->id) }}')">
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
    <form action="{{ route('settings.notification.delete') }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this notification? </strong></h4>
                            <p class="card-category">Deleting notification will also remove related data</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="notification_id" id="notification_id">
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
            $('#notification-list').DataTable();
        } );

        function confirmDelete(notification_id) {
            $('#deleteConfirmation').modal();
            $('#notification_id').val(notification_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#notification_id').val('');
        });
    </script>

    
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
