<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table">
                <tbody> 
                    <tr>
                        <th colspan=2 class="text-info text-uppercase"><h4><strong>SEO Options</h4></strong></th>
                    </tr>
                    <tr>
                        <th width="30%"><strong>Search Page Title</strong></th>
                        <td>
                            <input type="text" name="search_page_title" placeholder="Enter Meta Title" class="form-control" value="{{ $store->titleSettings->search_page_title }}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%"></th>
                        <td>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-primary">
                                    <input type="radio" name="titlesettings_options" id="option1" value="%search_title% - %site_title%"> Syntax 1
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" name="titlesettings_options" id="option2" value="%search_title% | %site_title%"> Syntax 2
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" name="titlesettings_options" id="option3" value="%search_title%"> Syntax 3
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%"><strong>404 Page Title Format</strong></th>
                        <td>
                            <input type="text" name="error_title_format" placeholder="Enter 404 Page Title" value="{{ $store->titleSettings->error_page_title }}" class="form-control">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>                    
    </div>
</div>