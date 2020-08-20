@extends('master')

@section('page_title')
Social Proof
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <h4 class="card-title"><strong> Social Proof <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Select social proof to display">info</i></strong></h4>
                <p class="card-category">Manage your social proof</p>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('socialProof.getNew', Session::get('subdomain')) }}" class="btn btn-sm btn-warning">Get new Social Proof</a>
                        <a href="{{ route('socialProof.create', Session::get('subdomain')) }}" class="pull-right btn btn-sm btn-warning">Create Social Proof</a>
                        @if(json_decode($store->socialProofSetting->settings)->display_order == 'random')
                        <a href="{{ route('socialProof.display.randomOrder', ['subdomain' => Session::get('subdomain'), 'type' => 'order']) }}" class="pull-right btn btn-sm btn-warning">Set to ordered display</a>
                        @else
                        <a href="{{ route('socialProof.display.randomOrder', ['subdomain' => Session::get('subdomain'), 'type' => 'random']) }}" class="pull-right btn btn-sm btn-warning">Seti to random display</a>
                        @endif
                        <a href="#" class="pull-right btn btn-sm btn-warning btn-save-ordering">Save Social Proof Ordering</a>
                    </div>
                    <div class="btn-group">
                        <a href="#" class="pull-right btn btn-warning btn-sm btn-delete-products">Delete Checked Products</a>
                        <a href="#" class="pull-right btn btn-warning btn-sm btn-display-products">Display Checked Social Proof</a>
                        <a href="#" class="pull-right btn btn-warning btn-sm btn-hide-products">Hide Checked Social Proof</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <form action="{{ route('socialProof.orderSocialProof', Session::get('subdomain')) }}" method="post" id="proofOrderForm">
        @csrf
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="social-proof-list">
                    <thead class="">
                        <tr>
                            <th></th>
                            <th class="display-hidden"></th>
                            <th>Title</th>
                            <th>Order</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="proof">
                    @foreach($socialProofs as $socialProof)
                        <tr>
                            <td width="5%"></td>
                            <td class="display-hidden">{{ $socialProof->id }}</td>
                            <td width="30%">{{ json_decode($socialProof->content)->title }}</td>
                            <td width="10%">
                                {{ $socialProof->order }}
                                <input type="hidden" name="orderProofs[]" value="{{ $socialProof->id }}">
                            </td>
                            <td class="text-center" width="40%">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a class="btn btn-sm btn-default"
                                        href="{{ route('socialProof.edit', [Session::get('subdomain'), $socialProof->id]) }}">
                                        <i class="material-icons">create</i>
                                    </a>
                                    <a class="btn btn-sm btn-default"
                                        href="#" onclick="confirmDelete('{{ Crypt::encrypt($socialProof->id) }}')">
                                        <i class="material-icons">delete</i>
                                    </a>

                                    @if($socialProof->active == 0)
                                    <a class="btn btn-sm btn-default" href="{{ route('socialProof.display', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($socialProof->id) ]) }}">
                                        Display
                                    </a>
                                    @else
                                    <a class="btn btn-sm btn-default" href="{{ route('socialProof.hide', ['subdomain' => Session::get('subdomain'), 'id' => Crypt::encrypt($socialProof->id) ]) }}">
                                        Hide
                                    </a>
                                    @endif

                                    <a class="btn btn-sm btn-default"
                                        href="#" 
                                        data-content='{{ $socialProof->content }}'
                                        onclick="preview(this, '{{ json_decode($socialProof->content)->title }}', '{{ $socialProof->type }}')">
                                        Preview
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

<div class="modal fade" id="selectProducts" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Select atleast one social proof</strong></h4>
                    </div>
                    <div class="card-body">
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="displayProofs" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('socialProof.display-hide', Session::get('subdomain')) }}" method="POST" id="displayProductForm">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to display all selected social proof? </strong></h4>
                            <p class="card-category">All selected will be displayed on your store</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="socialProofsId" id="displaySocialProofsId">
                                <input type="hidden" value="display" name="status">
                                <button type="submit" class="btn btn-primary btn-display-social">Yes</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="hideProofs" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('socialProof.display-hide', Session::get('subdomain')) }}" method="POST" id="hideProductForm">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to hide all selected social proof? </strong></h4>
                            <p class="card-category">Hiding all selected will no longer display in your store</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="socialProofsId" id="hideSocialProofsId">
                                <input type="hidden" value="hide" name="status">
                                <button type="submit" class="btn btn-primary btn-hide-social">Yes</button>
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
<div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('socialProof.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this social proof? </strong></h4>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="proof_id" id="proof_id">
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
<div class="modal fade" id="deleteSocialProof" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('socialProof.delete.multiple', Session::get('subdomain')) }}" method="POST" id="deleteSocialProofForm">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete selected social proof? </strong></h4>
                            <p class="card-category">Deleting social proof is not restorable.</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="deleteProducts" id="delSocialProofIds">
                                <button type="submit" class="btn btn-primary btn-delete-selected">Yes</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="selectSocialProof" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> Select atleast one social proof </strong></h4>
                    </div>
                    <div class="card-body">
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
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
    <script src="{!! asset('js/dataTables.checkboxes.min.js') !!}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script> 

    <script>
        $('[data-toggle="tooltip"]').tooltip({ html: true })

        var socialProofList = '';
        $(document).ready(function() {
            socialProofList = $('#social-proof-list').DataTable({
                'columnDefs': [
                    {
                        'targets': 0,
                        'data': 1,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }
                ],
                'select': {
                    'style': 'multi'
                },
                'rowCallback': function( row, data, index ){
                },
                'order': [[1, 'asc']]
            });
        });

        $('tbody.proof').sortable();

        $('.btn-display-products').on('click', function(e){
            var counter = socialProofList.columns().checkboxes.selected()[0].length;

            if(counter > 0){
                $('#displayProofs').modal();
            }else{
                $('#selectSocialProof').modal();
            }
        })

        $('.btn-hide-products').on('click', function(e){
            var counter = socialProofList.columns().checkboxes.selected()[0].length;

            if(counter > 0){
                $('#hideProofs').modal();
            }else{
                $('#selectSocialProof').modal();
            }
        })


        $('.btn-display-social').on('click', function(e){
            e.preventDefault();
            
            var ids = '';
            var checkboxes = socialProofList.columns().checkboxes.selected()[0];

            checkboxes.forEach(function(checkbox) {
                ids += checkbox + ',';
            });

            $('#displaySocialProofsId').val(ids);
            $('#displayProductForm').submit();
        })

        $('.btn-hide-social').on('click', function(e){
            e.preventDefault();
            
            var ids = '';
            var checkboxes = socialProofList.columns().checkboxes.selected()[0];

            checkboxes.forEach(function(checkbox) {
                ids += checkbox + ',';
            });

            $('#hideSocialProofsId').val(ids);
            $('#hideProductForm').submit();
        })

        $('.btn-save-ordering').on('click', function(){
            $('form#proofOrderForm').submit();
        })

        $("#displayProofs, #hideProofs").on("hidden.bs.modal", function () {
            $('#displaySocialProofsId').val('');
            $('#hideSocialProofsId').val('');
        });
    </script>
    <script>
        function stars(rating){
            var stars = '';

            for(var i = 1; i <= rating; i++){
                stars += '<i class="material-icons yellow-icon">star</i>';
            }

            return stars;
        }

        function preview(element, product, type) {
            var template = '';
            var data = JSON.parse($(element).attr('data-content'));
            var ratings = stars(data.ratings);

            if(type == 'review'){
                template += '<div data-notify="container" class="col-xs-11 col-sm-3 sp-container alert alert-{0}" role="alert">';
                template += '   <button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>';
                template += '   <img data-notify="icon" class="img-circle pull-left sp-img">';
                template += '   <span data-notify="title">{1}</span>';
                template += '   <span data-notify="message" class="msg">{2}</span>';
                template += '   <a href="{3}" target="{4}" data-notify="url"></a>';
                template += '</div>';
                var title = data.title.length > 63 ? data.title.substring(0, 60) + '...' : data.title;

                var notify = $.notify({
                    icon: data.image,
                    title: '<h5><strong>' + title + '</strong></h5>',
                    message: ratings + '<br>' + '<small>' + data.content + '</small>',
                    url: data.url,
                    target: '_blank'
                },{
                    placement: {
                        from: "bottom",
                        align: "left"
                    },
                    type: 'minimalist',
                    delay: 10000,
                    icon_type: 'image',
                    template: template,
                });  
            }else{
                template += '<div data-notify="container" class="col-xs-11 col-sm-3 sp-container-hits alert alert-{0}" role="alert">';
                template += '   <button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>';
                template += '   <img data-notify="icon" class="img-circle pull-left sp-img">';
                template += '   <span data-notify="title">{1}</span>';
                template += '   <span data-notify="message" class="msg">{2}</span>';
                template += '   <a href="{3}" target="{4}" data-notify="url"></a>';
                template += '</div>';
                var title = data.title.length > 63 ? data.title.substring(0, 60) + '...' : data.title;

                var notify = $.notify({
                    icon: data.image,
                    title: '<h5><strong>' + title + '</strong></h5>',
                    message: '<small>' + data.content + '</small>',
                    url: data.url,
                    target: '_blank'
                },{
                    placement: {
                        from: "bottom",
                        align: "left"
                    },
                    type: 'minimalist',
                    delay: 10000,
                    icon_type: 'image',
                    template: template,
                });  
            }
        }

        function confirmDelete(proof_id) {
            $('#deleteConfirmation').modal();
            $('#proof_id').val(proof_id);
        }

        $('.btn-delete-products').on('click', function(e){
            var counter = socialProofList.columns().checkboxes.selected()[0].length;

            if(counter > 0){
                $('#deleteSocialProof').modal();
            }else{
                $('#selectProducts').modal();
            }
        })

        $('.btn-delete-selected').on('click', function(e){
            e.preventDefault();
            
            var ids = '';
            var checkboxes = socialProofList.columns().checkboxes.selected()[0];

            checkboxes.forEach(function(checkbox) {
                ids += checkbox + ',';
            });

            $('#delSocialProofIds').val(ids);
            $('#deleteSocialProofForm').submit();
        })

        $("#deleteProducts").on("hidden.bs.modal", function () {
            $('#delSocialProofIds').val('');
        });
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#proof_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
