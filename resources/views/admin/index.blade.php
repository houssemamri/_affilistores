@extends('admin.master')

@section('page_title')
    Dashboard
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">account_box</i>
                </div>
                <p class="card-category">Total No. of Members</p>
                <h3 class="card-title">{{ $members }}</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">store</i>
                </div>
                <p class="card-category">Number of created stores by members</p>
                <h3 class="card-title">{{ $stores }}</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">poll</i>
                </div>
                <p class="card-category">Total votes from latest poll</p>
                <h3 class="card-title">{{ isset($poll->total_vote) ? $poll->total_vote : '0'  }}</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title"><strong> Login and Sign up Statistics </strong></h4>
                <p class="card-category">Logins and Sign ups for the past 21 days</p>
            </div>
            <div class="card-body">
                <canvas class="ct-chart">

                </canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{!! asset('js/chart.min.js') !!}"></script>
    <script>
        var datas = {!! $data !!};
        
        var ctx = $('.ct-chart');
            var chart = new Chart(ctx, {
                type: 'bar',

                // The data for our dataset
                data: {
                    labels:  datas.days,
                    datasets: [{
                        label: 'Login',
                        backgroundColor: '#03a9f4',
                        borderColor: '#0d47a1',
                        data: datas.login,
                    },{
                        label: 'Sign Up',
                        backgroundColor: '#f48fb1',
                        borderColor: '#e91e63',
                        data: datas.sign_up,
                    }]
                },

                // Configuration options go here
                options: {}
            });
    </script>
@endsection


