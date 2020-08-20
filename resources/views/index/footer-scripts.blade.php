
{!! isset($store->analytics->google_analytics_tracking_code) ? $store->analytics->google_analytics_tracking_code : '' !!}
{!! isset($store->analytics->third_party_analytics_tracking_code) ? $store->analytics->third_party_analytics_tracking_code : '' !!}
{!! isset($store->analytics->facebook_remarketing_pixel_script) ? $store->analytics->facebook_remarketing_pixel_script : '' !!}
{!! isset($store->analytics->webengage_tracking_id) ? $store->analytics->webengage_tracking_id : '' !!}
<script src="{!! asset('js/jquery.min.js') !!}"></script>
<script src="{!! asset('js/popper.min.js') !!}"></script>
<script src="{!! asset('js/bootstrap-material-design.min.js') !!}"></script>
{{-- <script src="{!! asset('js/perfect-scrollbar.jquery.min.js') !!}"></script> --}}
<!--  Notifications Plugin, full documentation here: http://bootstrap-notify.remabledesigns.com/    -->
<script src="{!! asset('js/bootstrap-notify.js') !!}"></script>
<!-- Material Dashboard Core initialisations of plugins and Bootstrap Material Design Library -->
<script src="{!! asset('js/material-dashboard.js?v=2.0.0') !!}"></script>
<!-- //axios -->
<script src="{!! asset('js/axios.min.js') !!}"></script>
<!-- Product Carousel -->
<script src="{!! asset('js/slick.min.js') !!}"></script>

<script src="{!! asset('js/cookie.js') !!}"></script>

@include('extra.alerts')

<script src="{!! asset('js/jquery.exitintent.min.js') !!}"></script>
@if(isset($exitpop) && Cookie::get('sent_email_exit_pop') == null)
    <script>
        let button = '';
        let heading = '';
        let body = '';

        $.exitIntent('enable');
        $(document).bind('exitintent', function() {
            if(Cookies.get('exit-pop-expire') !== 'true'){
                $('#exitpop').modal();
            }
        }); 
        
        $("#exitpop").on("hidden.bs.modal", function () {
            Cookies.set('exit-pop-expire', true, { expires: 0.8 });
        });
        
        var styles = JSON.parse('{!! $exitpop->styles !!}');
        
        $.each(styles.button, function(index, value) {
            button += index + ':' + value + ' !important;';
        })
        
        $('.btn-text').css('cssText', button);

        $.each(styles.heading, function(index, value) {
            // $('strong.heading').css(index, value);
            heading += index + ':' + value + ' !important;';
        })
        $('strong.heading').css('cssText', heading);


        $.each(styles.body, function(index, value) {
            // $('p.body').css(index, value);
            body += index + ':' + value + ' !important;';
        })
        $('p.body').css('cssText', body);

    </script>
@endif
<script>
    $(document).scroll(function (e) {
        console.log(e)
        var $nav = $(".fixed-top");
        $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());

        if($(this).scrollTop() > $nav.height()){
            $('.navbar-brand-image').hide();
            $('span.store-name').fadeIn("fast");
        }else{
            $('.navbar-brand-image').fadeIn("fast");
            $('span.store-name').hide();
        }
    });
</script>
@include('index.social-proof')