@extends('master')

@section('page_title')
Notifications
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> Notifications </strong></h4>
            <p class="card-category">Keep updated to news and announcements</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" >
                <thead class="">
                    <tr>
                        <th>Subject</th>
                        <th>Content</th>
                        <th>Created At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="notifications">
                    @foreach($notifications as $notification)
                        <tr class="{{ (in_array($notification->id, $openedNotifications)) ? '' : 'table-dark' }}" onclick="window.location = '{{ route('index.notifications.show', ['id' => Crypt::encrypt($notification->id)]) }}'">
                            <td width="20%">{{ $notification->subject }}</td>
                            <td width="60%">{!!  strlen($notification->body) > 100 ? substr(html_entity_decode(htmlspecialchars_decode($notification->body)), 0, 97) . "..." : $notification->body !!}</td>
                            <td width="10%">{{ date_format($notification->created_at, 'm/d/Y') }}</td>
                            <td width="10%">{!! (in_array($notification->id, $openedNotifications))  ? "<span class='badge badge-pill badge-success text-uppercase'>Read</span>" : "<span class='badge badge-pill badge-dark text-uppercase'>Unread</span>" !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('categories.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this category? </strong></h4>
                            <p class="card-category">Deleting this category will remove related data</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="category_id" id="category_id">
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
    <script>
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
