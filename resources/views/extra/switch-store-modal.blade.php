<div class="modal fade" id="switchStore" tabindex="-1" role="dialog" aria-labelledby="switchStore" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><strong> You only have one store and can't switch </strong></h4>
                        <p class="card-category">If you want to add a store please click this <a href="{{ route('listStore') }}"><u> Manage Stores </u></a></p>
                    </div>
                    <div class="card-body">
                        <div class="pull-right">
                            <input type="hidden" value="" name="product_id" id="product_id">
                            <button type="submit" class="btn btn-primary"  data-dismiss="modal">Okay</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>