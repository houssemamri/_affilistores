<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table">
                <tbody> 
                    <tr>
                        <th colspan=3 class="text-info text-uppercase"><h4><strong>RSS Feed</h4></strong></th>
                    </tr>
                    <tr>
                        <th width="20%"><strong>RSS All</strong></th>
                        <td class="text-center">
                            <input type="text" class="form-control" value="{{ route('index.category', ['subdomain' => Session::get('subdomain')]) }}" readonly>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('seo.rssfeed', Session::get('subdomain')) }}" target="_blank" class="btn btn-primary">Subscribe</a>
                            </div>
                        </td>
                      
                    </tr>
                    @foreach($categories as $category)
                    <tr>
                        <th width="20%"><strong>{{ $category->name }}</strong></th>
                        <td class="text-center">
                            <input type="text" value="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'category' => $category->permalink]) }}" class="form-control" readonly>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('seo.rssfeed', ['subdomain' => Session::get('subdomain'), 'categoryId' => $category->id ]) }}" target="_blank" class="btn btn-primary">Subscribe</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>                    
    </div>
</div>