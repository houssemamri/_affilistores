@extends('master')

@section('page_title')
Automations
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Automations </strong></h4>
                <p class="card-category">Set instructions to automate publishing of products </p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('automation.create', ['subdomain' => Session::get('subdomain')]) }}" class="pull-right btn btn-warning">Create New Automation</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-fixed text-center" id="automation-list">
                <thead class="">
                    <tr>
                        <th>Source</th>
                        <th>Category</th>
                        <th>Keyword</th>
                        <th>Daily Post</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Action </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($automations as $automation)
                        <tr>
                            <td>
                                @if(strcasecmp('amazon', $automation->source) == 0)
                                    <img src="{{ asset('img/amazon-icon.png') }}" alt="{{ $automation->source }}" width="50" class="img-fluid">
                                @elseif(strcasecmp('aliexpress', $automation->source) == 0)
                                    <img src="{{ asset('img/aliexpress-icon.png') }}" alt="{{ $automation->source }}" width="50" class="img-fluid">
                                @elseif(strcasecmp('ebay', $automation->source) == 0)
                                    <img src="{{ asset('img/ebay-icon.png') }}" alt="{{ $automation->source }}" width="50" class="img-fluid">
                                @elseif(strcasecmp('walmart', $automation->source) == 0)
                                    <img src="{{ asset('img/walmart-icon.jpg') }}" alt="{{ $automation->source }}" width="50" class="img-fluid">
                                @elseif(strcasecmp('shopcom', $automation->source) == 0)
                                    <img src="{{ asset('img/shopcom-icon.png') }}" alt="{{ $automation->source }}" width="100" class="img-fluid">
                                @elseif(strcasecmp('cjcom', $automation->source) == 0)
                                    <img src="{{ asset('img/cjcom-icon.png') }}" alt="{{ $automation->source }}" width="50" class="img-fluid">
                                @elseif(strcasecmp('jvzoo', $automation->source) == 0)
                                    <img src="{{ asset('img/jvzoo-icon.png') }}" alt="{{ $automation->source }}" width="50" class="img-fluid">
                                @elseif(strcasecmp('clickbank', $automation->source) == 0)
                                    <img src="{{ asset('img/clickbank-icon.png') }}" alt="{{ $automation->source }}" width="50" class="img-fluid">
                                @elseif(strcasecmp('warriorplus', $automation->source) == 0)
                                    <img src="{{ asset('img/warriorplus-icon.png') }}" alt="{{ $automation->source }}" width="50" class="img-fluid">
                                @elseif(strcasecmp('paydotcom', $automation->source) == 0)
                                    <img src="{{ asset('img/paydotcom-icon.png') }}" alt="{{ $automation->source }}" width="50" class="img-fluid">
                                @endif
                            </td>
                            <td>{{ $automation->category }}</td>
                            <td>{{ $automation->keyword }}</td>
                            <td>{{ $automation->number_daily_post }}</td>
                            <td>{{ date_format(date_create($automation->start_date), 'm/d/Y') }}</td>
                            <td>{{ date_format(date_create($automation->end_date), 'm/d/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('automation.edit', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($automation->id) ]) }}" class="btn btn-default">
                                        <i class="material-icons">create</i>
                                    </a>
                                    <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($automation->id) }}')" class="btn btn- btn-default">
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
    <form action="{{ route('automation.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this automation? </strong></h4>
                            <p class="card-category">Deleting this will stop automation of product posting</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="automation_id" id="automation_id">
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
            $('#automation-list').DataTable();
        } );

        function confirmDelete(automation_id) {
            $('#deleteConfirmation').modal();
            $('#automation_id').val(automation_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#automation_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
