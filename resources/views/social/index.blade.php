@extends('master')

@section('page_title')
Social Campaign
@endsection

@section('content')
@if(in_array('false', $socialCredentials))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <i class="material-icons">close</i>
    </button>
    @foreach($socialCredentials as $key => $credentials)
    @if($credentials == false)
    <span> <b> Please setup {{ ucwords($key) }} API settings </b></span>
    @endif
    @endforeach
    <br>
    <span><b>Go <a href="{{ route('social', Session::get('subdomain')) }}"><u>here</u></a> to setup API for Social Campaigns </b></span>
</div>
@endif

<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Social Campaign </strong></h4>
                <p class="card-category">Create social sharing of your products on different social media sites</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('social.create', ['subdomain' => Session::get('subdomain')]) }}" class="pull-right btn btn-warning">Create a Campaign</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-fixed text-center" id="category-list">
                <thead class="">
                    <tr>
                        <th>Social Campaign Name</th>
                        <th>Schedule Date & Time</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($socialCampaigns as $socialCampaign)
                    <tr>
                        <td>{{ $socialCampaign->name }}</td>
                        <td>{{ date_format(date_create($socialCampaign->schedule_date), 'm/d/Y') }} {{ date_format(date_create($socialCampaign->schedule_time), 'H:i a') }}</td>
                        <td>{{ date_format($socialCampaign->created_at, 'm/d/Y') }}</td>
                        <td>{!! $socialCampaign->enable_autopost == 1 ? "<span class='badge badge-pill badge-success'>Activated</span>" : "<span class='badge badge-pill badge-danger'>Deactivated</span>"  !!}</td>
                        <td>
                            <div class="btn-group" role="group" >
                                <a href="{{ route('social.edit', ['id' => Crypt::encrypt($socialCampaign->id), 'subdomain' => Session::get('subdomain') ]) }}" class="btn btn-block btn-default">
                                    <i class="material-icons">create</i>
                                </a>
                                <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($socialCampaign->id) }}')" class="btn btn-block btn-default">
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
    <form action="{{ route('social.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this social campaign? </strong></h4>
                            <p class="card-category">Deleting this will remove automated posting to social sites.</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="social_id" id="social_id">
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

        function confirmDelete(social_id) {
            $('#deleteConfirmation').modal();
            $('#social_id').val(social_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#social_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
