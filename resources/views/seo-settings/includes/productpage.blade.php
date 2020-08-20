<div class="row">
    <div class="col-lg-7">
        <div class="table-responsive">
            <table class="table">
                <tbody> 
                    <tr>
                        <th colspan=2 class="text-info text-uppercase"><h4><strong>SEO Options</h4></strong></th>
                    </tr>
                    <tr>
                        <th width="30%"><strong>Meta Title</strong></th>
                        <td>
                            <input type="text" name="meta_title" placeholder="Enter Meta Title" class="form-control" value="{{ $store->productPage->meta_title }}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%"></th>
                        <td>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-primary">
                                    <input type="radio" name="options" id="option1" value="%product_title% - %site_title%"> Syntax 1
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" name="options" id="option2" value="%product_title% | %site_title%"> Syntax 2
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" name="options" id="option3" value="%product_title% "> Syntax 3
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Robots Meta NoIndex</th>
                        <td>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="robots_meta_no_index" {{ ($store->productPage->robots_meta_no_index == 1) ? 'checked' : '' }}>
                                Set to active
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Robots Meta NoFollow</th>
                        <td>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="robots_meta_no_follow" {{ ($store->productPage->robots_meta_no_follow == 1) ? 'checked' : '' }}>
                                Set to active
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>                    
    </div>
    <div class="col-lg-5">
    
    </div>
</div>
