@extends('subadmin.master')

@section('page_title')
IPN Requests
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> IPN Requests </strong></h4>
        <p class="card-category">Tract yout IPN Requests here</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table " id="ipns-list">
                <thead class="">
                    <tr>
                        <th>Date</th>
                        <th class="text-uppercase">ctransreceipt</th>
                        <th class="text-uppercase">ccustemail</th>
                        <th class="text-uppercase">ccustname</th>
                        <th class="text-uppercase">ctransvendor</th>
                        <th class="text-uppercase">cproditem</th>
                        <th class="text-uppercase">cprodtype</th>
                        <th class="text-uppercase">ctransaction</th>
                        <th class="text-uppercase">ctransamount</th>
                        <th class="text-uppercase">ctranstime</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ipns as $ipn)
                        <tr>
                            <td>{{ date_format($ipn->created_at, 'F d, Y') }}</td>
                            <td>{{ $ipn->ctransreceipt }}</td>
                            <td>{{ $ipn->ccustemail }}</td>
                            <td>{{ $ipn->ccustname }}</td>
                            <td>{{ $ipn->ctransvendor }}</td>
                            <td>{{ $ipn->cproditem }}</td>
                            <td>{{ $ipn->cprodtype }}</td>
                            <td>{{ $ipn->ctransaction }}</td>
                            <td>{{ $ipn->ctransamount }}</td>
                            <td>{{ date('Y-m-d H:i:s', $ipn->ctranstime) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('custom-scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#ipns-list').DataTable({
                "ordering": false
            });
        } );
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
