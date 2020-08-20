@extends('master')

@section('page_title')
Products Reviews
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Product: {{ $product->name }} </strong></h4>
        <p class="card-category">Approved and Disapprove Product Reviews</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table " id="product-list">
                <thead class="">
                    <tr>
                        <th >Customer Name</th>
                        <th >Customer Email</th>
                        <th class="text-center">Customer Ratings</th>
                        <th class="text-center">Review</th>
                        <th>Date Reviewed</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product->reviews as $review)
                        <tr>
                            <td>{{ $review->name }}</td>
                            <td >{{ $review->email }}</td>
                            <td class="text-center">{{ $review->ratings }} Stars</td>
                            <td width="40%">{{ $review->review }}</td>
                            <td>{{ date_format($review->created_at, 'm/d/Y H:i a') }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a class="btn btn-sm btn-default {{ $review->approved == 1 ? 'active disabled' : ''}}" href="{{ route('products.reviews.approveDisapprove', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($review->id), 'status' => '1']) }}">Approve</a>
                                    <a class="btn btn-sm btn-default {{ $review->approved == 0 ? 'active disabled' : ''}}" href="{{ route('products.reviews.approveDisapprove', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($review->id), 'status' => '0']) }}">Disapprove</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('products.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this product? </strong></h4>
                            <p class="card-category">Deleting this product will remove related data</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="product_id" id="product_id">
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
            $('#product-list').DataTable();
        });

        function confirmDelete(product_id) {
            $('#deleteConfirmation').modal();
            $('#product_id').val(product_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#product_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
