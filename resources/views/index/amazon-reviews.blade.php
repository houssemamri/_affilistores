@if($product->source == 'amazon')
<script>
        $(window).on('load', function () {
        var url = '{{ route("amazon.customerReviews", ["subdomain" => $store->subdomain, "id" => $product->reference_id]) }}';
        axios.get(url)
        .then(function (response) {
            if(response){
                var item = response.data.Items.Item.CustomerReviews;
                if(item.HasReviews){
                    $('.customer-reviews').show();
                    $('.customer-reviews iframe').attr('src', item.IFrameURL);
                }else{
                    $('.customer-reviews').hide();
                    $('.customer-reviews iframe').removeAttr('src');
                }
            }else{
                $('.customer-reviews').hide();
                $('.customer-reviews iframe').removeAttr('src');
            }
        })
        .catch(function (error) {
            console.log(error);
        });
    });
    
</script>
@endif