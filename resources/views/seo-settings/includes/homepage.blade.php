<div class="row">
    <div class="col-lg-7">
        <div class="table-responsive">
            <table class="table">
                <tbody> 
                    <tr>
                        <th>Website Name <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set your homepage website name">info</i></th>
                        <td>
                            <input type="text" name="website_name" placeholder="Enter Website Name" value="{{ $store->homepage->website_name }}" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th colspan=2 class="text-info text-uppercase"><h4><strong>Seo Settings</h4></strong></th>
                    </tr>
                    <tr>
                        <th width="30%"><strong>Meta Title <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add Meta Title of your homepage to increase search ranking">info</i></strong></strong></th>
                        <td class="hompage-meta-title">
                            <input type="text" name="meta_title" placeholder="Enter Meta Title" value="{{ $store->homepage->meta_title }}" class="form-control" maxlength="70">
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%"><strong>Meta Description <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add Meta Description of your homepage to increase search ranking">info</i></strong></th>
                        <td class="hompage-meta-description">
                            <textarea name="meta_description" class="form-control"  cols="1" rows="5" maxlength="170">{{ $store->homepage->meta_description }}</textarea>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%"><strong>Meta Keywords <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add Meta Keywords of your homepage to increase search ranking. Max 8 keywords can be added">info</i></strong></th>
                        <td class="hompage-meta-keywords">
                            <input type="text" name="meta_keywords" placeholder="Enter Meta Keywords" value="{{ $store->homepage->meta_keywords }}">
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Robots Meta NoIndex <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you don't want your product be rank in search engines">info</i></th>
                        <td>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="robots_meta_no_index" {{ $store->homepage->robots_meta_no_index == 1 ? 'checked' : '' }}>
                                Set to active
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Robots Meta NoFollow <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you don't want your product be rank in search engines">info</i></th>
                        <td>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="robots_meta_no_follow" {{ $store->homepage->robots_meta_no_follow == 1 ? 'checked' : '' }}>
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
    <div class="col-lg-5 homepage-seo">
        <h4 class="text-info text-uppercase"><strong>Preview</h4></strong>
        <p>Show information about the snippet editor	This is a rendering of what this post might look like in Google's search results. Read this post for more info.</p>

        <p class="meta-title-text"> </p>
        <p class="url">{{ route('index', ['subdomain' => Session::get('subdomain')]) }}</p>
        <p class="meta-description-text"> </p>

        <h4 class="text-info text-uppercase"><strong>SEO Analysis</h4></strong>

        <ul>
            <li class="meta-title-length">
                <span class="seo-score-icon"></span> 
                <span class="pre-text"> Meta title text contains 1 character. </span>
                <span class="text">This is far too low and should be increased text up to 60 characters</span>
            </li>
            <li class="meta-description-length">
                <span class="seo-score-icon"></span> 
                <span class="pre-text">No meta description has been specified. </span>
                <span class="text">Search engines will display copy from the page instead.</span>
            </li>
            <li class="meta-keyword-length">
                <span class="seo-score-icon"></span>
                <span class="pre-text"> Meta Keywords contains 0 keywords. Maximum 8 keyword accepted </span>
            </li>
        </ul>
    </div>
</div>