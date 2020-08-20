@if(in_array('social proofs', $features))
<script>
    var socialProofs = '';
    var url = '{{ route("index.social.proofs", $store->subdomain) }}';
    
    function stars(rating){
        var stars = '';

        for(var i = 1; i <= rating; i++){
            stars += '<i class="material-icons yellow-icon">star</i>';
        }

        return stars;
    }

    function preview(data, product, type) {
        var template = '';
        var data = JSON.parse(data);
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
                delay: data.settings.display_time * 1000,
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
                delay: data.settings.display_time * 1000,
                icon_type: 'image',
                template: template,
            });  
        }

        return notify;
    }

    $(window).on('load', function(){
        axios.get(url)
        .then(function(response){
            socialProofs = response.data;
            
            $.each(socialProofs, function (index, proof) {
                var data = JSON.parse(proof.data);
                // var interval = (parseInt(data.settings.time_difference) + parseInt(data.settings.display_time)) * 1000;
                var interval = ((parseInt(data.settings.time_difference) + parseInt(data.settings.display_time)) * 1000) * 2;

                setTimeout(function(){
                    console.log(interval)
                    preview(proof.data, proof.product, proof.type)
                }, interval); 
            });

        })
        .catch(function(error){
            console.log(error);
        })
    })
</script>
@endif