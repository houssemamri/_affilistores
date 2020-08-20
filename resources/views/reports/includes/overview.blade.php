<div class="table-responsive">
    <table class="table table-fixed" id="product-list">
        <thead>
            <tr>
                <th>Product Name</th>
                <th class="text-center">Product Id</th>
                <th class="text-center">Product Clicks</th>
                <th class="text-center">Affiliate Clicks</th>
                <th class="text-center">Post Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td width="50%">{{ $product->name }}</td>
                <td class="text-center">{{ $product->reference_id }}</td>
                <td class="text-center">{{ $product->hits->sum('page_hits') }}</td>
                <td class="text-center">{{ $product->hits->sum('affiliate_hits') }}</td>
                <td class="text-center">{{ date_format(date_create($product->published_date), 'Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>