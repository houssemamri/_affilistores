@extends('admin.master')

@section('page_title')
Memberships
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Memberships </strong></h4>
                <p class="card-category">Manage your memberships</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('memberships.add') }}" class="pull-right btn btn-warning">Create New Membership</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <table class="table">
            <tbody>
                <tr>
                    <th width="10%">JVZoo IPN URL</th>
                    <td width="90%"><strong class="urls"><em>{{ route('api.jvzoo') }}</em></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table membership-table" id="membership-list">
                <thead class="">
                    <tr>
                        <th>Title</th>
                        <th>JVZoo Product Id</th>
                        <th>Price</th>
                        <th>Frequency</th>
                        <th>No. of Stores Per Month</th>
                        <th>Trial Period</th>
                        <th>Trial Price</th>
                        <th>Members</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($memberships as $membership)
                        <tr>
                            <td>{{ $membership->title }}</td>
                            <td>{{ $membership->jvzoo_product_id }}</td>
                            <td>{{ $membership->product_price }}</td>
                            <td>
                                @if($membership->frequency == 1)
                                    Monthly
                                @elseif($membership->frequency == 3)
                                    Quarterly
                                @elseif($membership->frequency == 12)
                                    Yearly
                                @elseif($membership->frequency == 120)
                                    Lifetime
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $membership->stores_per_month == 1000 ? "Unlimited" : $membership->stores_per_month . ' Per Month' }}</td>
                            </td>
                            <td>{{ (isset($membership->trial_period)) ? $membership->trial_period : 'N/a' }}</td>
                            <td>{{ (isset($membership->trial_price)) ? $membership->trial_price : 'N/a' }}</td>
                            <td>{{ $membership->members->count() }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a class="btn btn-sm btn-default" href="{{ route('memberships.edit', Crypt::encrypt($membership->id)) }}" onclick="">
                                        <i class="material-icons">create</i>
                                    </a>
                                    <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($membership->id) }}')">
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
    <form action="{{ route('memberships.delete') }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this membership? </strong></h4>
                            <p class="card-category">Deleting membership will remove related data.</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="membership_id" id="membership_id">
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
            $('#membership-list').DataTable({
                "pageLength": 25
            });
        } );

        function confirmDelete(membership_id) {
            $('#deleteConfirmation').modal();
            $('#membership_id').val(membership_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#membership_id').val('');
        });
    </script>
    <script>
        $('.urls').on('click', function(){
            var temp = $("<input>");
            $("body").append(temp);
            temp.val($(this).text()).select();
            document.execCommand("copy");
            temp.remove();

            $.notify({
                icon: "info",
                    message: 'Copied to clipboard',
                },{
                    type: 'info'
                });
            })
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
