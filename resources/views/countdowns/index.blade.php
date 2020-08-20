@extends('master')

@section('page_title')
Countdown Timers
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Countdown Timers <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Manage your product countdown timers here. Countdown Timers will be shown on product page.">info</i></strong></h4>
                <p class="card-category">Manage your countdown timers</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('countdowns.create', Session::get('subdomain')) }}" class="pull-right btn btn-warning">Create New Countdown</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table " id="countdown-list">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Product</th>
                        <th>Countdown Date</th>
                        <th>Access Link</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($countdowns as $countdown)
                    <tr>
                        <td>{{ $countdown->name }}</td>
                        <td><a href="{{ route('index.product.show', [Session::get('subdomain'), $countdown->product->permalink]) }}" target="_blank">{{ $countdown->product->name }}</a></td>
                        <td>{{ date_format(date_create($countdown->countdown_date), 'Y-m-d H:i:s') }}</td>
                        <td>{{ $countdown->access_link }}</td>
                   
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-sm btn-default" href="{{ route('countdowns.edit', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($countdown->id) ]) }}" >
                                    <i class="material-icons">create</i>
                                </a>
                                <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($countdown->id) }}')">
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
    <form action="{{ route('countdowns.delete', Session::get('subdomain')) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this countdown timer? </strong></h4>
                            <p class="card-category">Deleting blog can't be restored</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="countdown_id" id="countdown_id">
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
        $('[data-toggle="tooltip"]').tooltip({ html: true })

        $(document).ready(function() {
            $('#countdown-list').DataTable();
        } );

        function confirmDelete(countdown_id) {
            $('#deleteConfirmation').modal();
            $('#countdown_id').val(countdown_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#countdown_id').val('');
        });
    </script>

    
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
