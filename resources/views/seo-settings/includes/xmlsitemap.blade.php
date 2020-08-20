<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table">
                <tbody> 
                    <tr>
                        <th colspan=3 class="text-info text-uppercase"><h4><strong>XML Sitemap</h4></strong></th>
                    </tr>
                    <tr>
                        <th width="20%"><strong>XML Sitemap</strong></th>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('index.xmlSiteMap', Session::get('subdomain')) }}" class="btn btn-primary" download>Download Sitemap</a>
                                <a href="{{ route('index.xmlSiteMap', Session::get('subdomain')) }}" target="_blank" class="btn btn-primary">Visit Sitemap Link</a>
                            </div>
                        </td>
                        <td class="text-center">
                            <h4>Please copy your sitemap url and put in your webmaster account.</h4>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>                    
    </div>
</div>