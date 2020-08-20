    <!-- Facebook Metas START-->
    <meta property="fb:app_id" content="{{ json_decode($store->socialSettings->where('name', 'facebook')->first()->settings)->application_id }}"/>
    <meta property="og:url"                content="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $product->permalink]) }}" />
    <meta property="og:type"               content="post" />
    <meta property="og:title"              content="{{ $product->name }}" />
    <meta property="og:description"        content="{{ isset($product->seoSettings->meta_description)  ? $product->seoSettings->meta_description : html_entity_decode(strip_tags(preg_replace('/<style>(.*?)<\/style>/s', '', html_entity_decode(htmlspecialchars_decode($product->description))))) }}" />
    <meta property="og:image"              content="{{ $product->image }}" />
    <meta property="og:image:secure_url" content="{{ $product->image }}" /> 
    <meta property="og:image:type" content="image/jpeg" /> 
    @foreach($product->images->where('type', 'default') as $image)
    <meta property="og:image" content="{{ $image->image}}" />
    <meta property="og:image:secure_url" content="{{ $image->image }}" /> 
    @endforeach
    <!-- Facebook Metas END-->

    <!-- Twitter Tags For sharing START-->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{{ route('index', ['subdomain' => $store->subdomain]) }}">
    <meta name="twitter:title" content="{{ $product->name }}">
    <meta name="twitter:description" content="{{ isset($product->seoSettings->meta_description)  ? $product->seoSettings->meta_description : html_entity_decode(strip_tags(preg_replace('/<style>(.*?)<\/style>/s', '', html_entity_decode(htmlspecialchars_decode($product->description))))) }}">
    <meta name="twitter:image" content="{{ $product->image }}">
    <!-- Twitter Tags For sharing END-->