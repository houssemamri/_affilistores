@extends('master')

@section('page_title')
Newsletters
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <h4 class="card-title"><strong> Newsletters </strong></h4>
                <p class="card-category">Manage your newsletters</p>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('newsletters.subscribers', ['subdomain' => Session::get('subdomain')]) }}" class="pull-right btn btn-sm btn-warning">Subscribers</a>
                        <a href="{{ route('newsletters.create', ['subdomain' => Session::get('subdomain')]) }}" class="pull-right btn btn-sm btn-warning">Create Newsletter</a>
                        {{-- <a href="{{ route('newsletters.getresponse', ['subdomain' => Session::get('subdomain')]) }}" class="pull-right btn btn-sm btn-warning">Connect Subscribers to GetResponse</a> --}}
                        <a href="#" data-toggle="modal" data-target="#chooseEmailAutoresponder" class="pull-right btn btn-sm btn-warning">Connect subscribers to Email Autoresponder</a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-fixed text-center" id="category-list">
                <thead class="">
                    <tr>
                        <th>Subject</th>
                        <th>Body</th>
                        <th>Created At</th>
                        <th>Number of Sent</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($newsletters as $newsletter)
                        <tr>
                            <td>{{ $newsletter->subject }}</td>
                            <td>{!! $newsletter->body !!}</td>
                            <td>{{ date_format($newsletter->created_at, 'm/d/Y') }}</td>
                            <td><span class='badge badge-pill badge-success'>{{ $newsletter->sents->count() }}</span></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('newsletters.send', ['subdomain' => Session::get('subdomain'), 'id' => $newsletter->id ]) }}" class="btn btn-default">
                                        Send
                                    </a>

                                    <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($newsletter->id) }}')" class="btn btn- btn-default">
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
    <form action="{{ route('newsletters.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this newsletter? </strong></h4>
                            <p class="card-category">Deleting this newsletter will remove related data</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="newsletter_id" id="newsletter_id">
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

<!-- Modal -->
<div class="modal fade" id="chooseEmailAutoresponder" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header card-header-primary ">
                        <div class="card-icon card-header-warning">
                            <i class="material-icons">mail</i>
                        </div>
                        <h3 class="card-title">Select Email Responder</h3>
                        <p class="card-category">Setup Email Responder and autmatically add your subscribers to your campaigns</p>
                    </div>
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-lg-4 flex">
                                <a href="{{ route('newsletters.getresponse', ['subdomain' => Session::get('subdomain')]) }}" >
                                    <img src="{{ asset('img/getresponse.jpg') }}" alt="Get Response" class="img-fluid">
                                </a>
                            </div>
                            @foreach($responders as $key => $responder)
                            <div class="col-lg-4 flex">
                                <a href="{{ $key !== 'infusionsoft' ? route('autoresponder.create', ['source' => $key, 'subdomain' => Session::get('subdomain') ]) : '#'}}">
                                    <img src="{{ asset('img/' . $key . '.jpg') }}" alt="{{ $responder }}" class="img-fluid">
                                    @if($key == 'infusionsoft')
                                    <p class="text-muted">SOON!</p>
                                    @endif
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#category-list').DataTable();
        } );

        function confirmDelete(newsletter_id) {
            $('#deleteConfirmation').modal();
            $('#newsletter_id').val(newsletter_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#newsletter_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
