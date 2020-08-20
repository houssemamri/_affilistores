<link rel="stylesheet" type="text/css" href="{!! asset('css/font.css') !!}" />
<link rel="stylesheet" type="text/css" href="{!! asset('css/font-awesome.min.css') !!}" />
<link rel="stylesheet" type="text/css" href="{!! asset('css/material-dashboard.min.css') !!}" />
<link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}" />
<link rel="stylesheet" type="text/css" href="{!! asset('css/responsive.css') !!}" />

@if(isset($countdownTimer) && $countdownTimer->countdown_date >= date('Y-m-d'))
<link rel="stylesheet" href="{!! asset('css/flipclock.min.css') !!}">

<style>
    .fixed-footer{
        @foreach(json_decode($countdownTimer->settings) as $cKey => $cTimer)
            {{ str_replace('_', '-', $cKey) }} : {{ $cTimer . ';' }} 
        @endforeach
    }

    .fixed-footer .close{
        {{ str_replace('_', '-', $cKey) }} : {{ $cTimer . ';' }} 
    }

    .flip-clock-divider .flip-clock-label{
        {{ str_replace('_', '-', $cKey) }} : {{ $cTimer . ';' }} 
    }
</style>
@endif