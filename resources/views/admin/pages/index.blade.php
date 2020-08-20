@extends('admin.master')

@section('page_title')
Pages
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Front End Pages </strong></h4>
                <p class="card-category">Manage your front pages</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('pages.add', 'front_end') }}" class="pull-right btn btn-warning">Create New Page</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-fixed" id="front-end-page-list">
                <thead class="">
                    <tr>
                        <th>Title</th>
                        <th class="text-center">Available For</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($frontEndPages as $frontEndPage)
                        <tr>
                            <td>{{ $frontEndPage->title }}</td>
                            <td class="text-center">
                                @foreach($frontEndPage->available as $available)
                                <span class='badge badge-pill badge-info'>{{ $available->membership->title }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a class="btn btn-sm btn-default" href="{{ route('pages.edit', Crypt::encrypt($frontEndPage->id)) }}" onclick="">
                                        <i class="material-icons">create</i>
                                    </a>
                                    <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($frontEndPage->id) }}')">
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

<div class="card">
    <form action="{{ route('pages.set-ordering') }}" method="post">
        {!! csrf_field() !!}
        <div class="card-header card-header-primary">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <h4 class="card-title"><strong> Member Pages </strong></h4>
                    <p class="card-category">Manage your member pages</p>
                </div>
                <div class="col-lg-4 col-md-12">
                    <a href="{{ route('pages.add', 'member') }}" class="pull-right btn btn-warning">Create New Page</a>
                    <button type="submit" class="pull-right btn btn-warning">Save Page Ordering</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-fixed" id="member-page-list">
                    <thead class="">
                        <tr>
                            <th>Title</th>
                            <th class="text-center">Available For</th>
                            <th class="text-center">Order</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="member">
                        @foreach($memberPages as $memberPage)
                            <tr>
                                <td>{{ $memberPage->title }}</td>
                                <td class="text-center">
                                    @foreach($memberPage->available as $available)
                                    <span class='badge badge-pill badge-info'>{{ $available->membership->title }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    {{ $memberPage->order }}
                                    <input type="hidden" name="pageOrders[]" value="{{ $memberPage->id }}">
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a class="btn btn-sm btn-default" href="{{ route('pages.edit', Crypt::encrypt($memberPage->id)) }}" onclick="">
                                            <i class="material-icons">create</i>
                                        </a>
                                        <a class="btn btn-sm btn-default" href="#" onclick="confirmDelete('{{ Crypt::encrypt($memberPage->id) }}')">
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
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('pages.delete') }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this page? </strong></h4>
                            <p class="card-category">Deleting this page will not be available anymore.</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="page_id" id="page_id">
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
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script> 
    <script>
        $(document).ready(function() {
            $('#front-end-page-list').DataTable();
        } );

        $(document).ready(function() {
            $('#member-page-list').DataTable({
                "order": [[ 3, "asc" ]]
            });
        });

        $('tbody.member').sortable();
        

        function confirmDelete(page_id) {
            $('#deleteConfirmation').modal();
            $('#page_id').val(page_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#page_id').val('');
        });
    </script>

@endsection

@section('alert')
    @include('extra.alerts')
@endsection
