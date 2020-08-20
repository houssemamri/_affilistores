@extends('master')

@section('page_title')
Reports
@endsection

@section('content')

<div class="card">
  <div class="card-header card-header-primary">
    <div class="nav-tabs-navigation">
      <div class="nav-tabs-wrapper">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="nav-item">
                <a class="nav-link active show" href="#overview" data-toggle="tab">
                    Overview <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Overview report of your products page hits and affiliate hits.">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#products-report" data-toggle="tab">
                    Products <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Product publishing reports. Can be filter by week, month, year and custom date period">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#hits" data-toggle="tab">
                    Clicks <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Product clicks and affiliate clicks reports. Can be filter by week, month, year and custom date period">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#social" data-toggle="tab">
                    Social <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Product sharing to social media sites reports">info</i>
                    <div class="ripple-container"></div>
                </a>
            </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane fade show active" id="overview">
        @include('reports.includes.overview')
      </div>
      <div class="tab-pane fade" id="products-report">
        @include('reports.includes.product')
      </div>
      <div class="tab-pane fade" id="hits">
        @include('reports.includes.hits')
      </div>
      <div class="tab-pane fade" id="social">
        @include('reports.includes.social')
      </div>
    </div>
  </div>
</div>
@endsection

@section('alert')
  @include('extra.alerts')
@endsection

@section('custom-scripts')
<script src="{!! asset('js/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('js/dataTables.bootstrap4.min.js') !!}"></script>
<script src="{!! asset('js/chart.min.js') !!}"></script>
<script src="{!! asset('js/axios.min.js') !!}"></script>
<script>
    $('[data-toggle="tooltip"]').tooltip()

    $(document).ready(function() {
        $('#product-list').DataTable();
    } );

     $(document).ready(function() {
        $('#social-list').DataTable();
    } );
</script>

<script>
    var weekly = {!! $weeklyPosts !!};
    var monthly = {!! $monthlyPosts !!};
    var yearly = {!! $yearlyPosts !!};

    var chartData = {
        labels: weekly.days,
            datasets: [{
                label: 'Published',
                backgroundColor: '#03a9f4',
                borderColor: '#0d47a1',
                data: weekly.published,
            },{
                label: 'Drafts',
                backgroundColor: '#f48fb1',
                borderColor: '#e91e63',
                data: weekly.drafts,
            }]
    }
    var ctx = $('.ct-chart');
    var chart = new Chart(ctx, {
        type: 'line',

        // The data for our dataset
        data: chartData,

        // Configuration options go here
        options: {}
    });

    function showErrorMsg(msg){
        $.notify({
            icon: "error",
            message: msg,
        },{
            type: 'danger'
        });
    }

    function changeData(selected = null, chart, chartData){
        var data = {};
        $('.custom-date').hide();
            
        if (selected == 'week')
            data = weekly;
        if (selected == 'month') 
            data = monthly;
        if (selected == 'year')
            data = yearly;
        if (selected == 'custom'){
            $('.custom-date').show();
            data = weekly;
            
            $('.btn-show').on('click',function(){
                var start = $('.start-date').val();
                var end = $('.end-date').val();
                
                if(!(start && end))
                    showErrorMsg('Please enter from date and to date');

                var URL = '{{ route("reports.customDate", ["subdomain" => Session::get("subdomain"), "startDate" => "sDate", "endDate" => "eDate"]) }}';
                URL = URL.replace('sDate', start);
                URL = URL.replace('eDate', end);
                
                axios.get(URL)
                .then(function(response){
                    if(response.data.success){
                        data = JSON.parse(response.data.custom);

                        chartData.labels = data.days;
                        $(chartData.datasets)[0].data = data.published;
                        $(chartData.datasets)[1].data = data.drafts;

                        chart.update();
                    }else{
                        showErrorMsg(response.data.msg);
                    }
                })
                .catch(function(error){
                    showErrorMsg('Oops! Something went wrong. Please Try Again');
                })
            });
        }

        chartData.labels = data.days;
        $(chartData.datasets)[0].data = data.published;
        $(chartData.datasets)[1].data = data.drafts;

        chart.update();
    }

    $('select#product-period').change(function(){
        var option = $(this).find(':selected');
        changeData(option.val(), chart, chartData)
    });

    $('input.start-date').change(function(){
        if($(this).val()){
            $('input.end-date').attr('min', $(this).val());
            $('input.end-date').removeAttr('disabled');
        }
    });

    $('input.end-date').change(function(){
        if($(this).val()){
            $('input.start-date').attr('max', $(this).val());
        }
    });
    
</script>


<script>
    var weeklyHits = {!! $weeklyHits !!};
    var monthlyHits = {!! $monthlyHits !!};
    var yearlyHits = {!! $yearlyHits !!};

    var chartDataHits = {
        labels: weeklyHits.days,
            datasets: [{
                label: 'Product Clicks',
                backgroundColor: '',
                borderColor: '#ffc107',
                data: weeklyHits.product_hits,
            },{
                label: 'Affiliate Clicks',
                backgroundColor: '',
                borderColor: '#160758',
                data: weeklyHits.affiliate_hits,
            }]
    }
    var ctx = $('.hits-ct-chart');
    var chartHits = new Chart(ctx, {
        type: 'line',

        // The data for our dataset
        data: chartDataHits,

        // Configuration options go here
        options: {}
    });

    function changeDataHits(selected = null, chartHits, chartDataHits){
        var dataHits = {};
        $('.hits-custom-date').hide();
            
        if (selected == 'week')
            dataHits = weeklyHits;
        if (selected == 'month') 
            dataHits = monthlyHits;
        if (selected == 'year')
            dataHits = yearlyHits;
        if (selected == 'custom'){
            $('.hits-custom-date').show();
            dataHits = weeklyHits;
            
            $('.hits-btn-show').on('click',function(){
                var start = $('.hits-start-date').val();
                var end = $('.hits-end-date').val();
                
                if(!(start && end))
                    showErrorMsg('Please enter from date and to date');

                var URL = '{{ route("reports.customDateHits", ["subdomain" => Session::get("subdomain"), "startDate" => "sDate", "endDate" => "eDate"]) }}';
                URL = URL.replace('sDate', start);
                URL = URL.replace('eDate', end);
                
                axios.get(URL)
                .then(function(response){
                    if(response.data.success){
                        dataHits = JSON.parse(response.data.custom);

                        chartDataHits.labels = dataHits.days;
                        $(chartDataHits.datasets)[0].data = dataHits.product_hits;
                        $(chartDataHits.datasets)[1].data = dataHits.affiliate_hits;

                        chartHits.update();
                    }else{
                        showErrorMsg(response.data.msg);
                    }
                })
                .catch(function(error){
                    showErrorMsg('Oops! Something went wrong. Please Try Again');
                })
            });
        }

        chartDataHits.labels = dataHits.days;
        $(chartDataHits.datasets)[0].data = dataHits.product_hits;
        $(chartDataHits.datasets)[1].data = dataHits.affiliate_hits;

        chartHits.update();
    }

    $('select#product-hits-period').change(function(){
        var option = $(this).find(':selected');
        changeDataHits(option.val(), chartHits, chartDataHits)
    });

    $('input.hits-start-date').change(function(){
        if($(this).val()){
            $('input.hits-end-date').attr('min', $(this).val());
            $('input.hits-end-date').removeAttr('disabled');
        }
    });

    $('input.hits-end-date').change(function(){
        if($(this).val()){
            $('input.hits-start-date').attr('max', $(this).val());
        }
    });
    
</script>
@endsection