@extends('master')

@section('page_title')
Notifications
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <h4 class="card-title"><strong> {{ $notification->subject }} </strong></h4>
                <p class="card-category">{{ date_format($notification->created_at, 'F d, Y H:i a') }}</p>
            </div>
            <div class="col-lg-4 col-sm-12">
                <div class="pull-right">
                    <a class="btn btn-secondary" href="{{ route('index.notifications', Session::get('subdomain')) }}">Back to Notifications</a>
                </div>
            </div>
        </div>

       
    </div>
    <div class="card-body">
        {!! htmlspecialchars_decode($notification->body) !!}
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
