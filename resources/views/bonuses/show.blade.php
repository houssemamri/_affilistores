@extends('master')

@section('page_title')
    Bonuses
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-primary ">
                <div class="row">
                    <div class="col-lg-10 col-sm-12">
                        <h4 class="card-title"><strong> {{ $bonus->title }} </strong></h4>
                    </div>
                    <div class="col-lg-2 col-sm-12 pull-right">
                        <a href="{{ route('bonus.index', $store->subdomain) }}" class="btn btn-info btn-xs">Go Back to Bonuses</a>
                    </div>
                </div>
            </div>
            <div class="card-body text-center ecover">
                {!! $bonus->body !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
    @if($bonus->id = 2)
    <script>
        $('.ecover p > img').each(function(){
            var data = $(this).attr('alt').split(", ");
            var url = "{{ route('bonus.ecover', ['subdomain' => $store->subdomain, 'name' => 'data-name', 'id' => 'data-id' ]) }}";
            url = url.replace('data-name', data[0].trim());
            url = url.replace('data-id', data[1].trim());

            $(this).wrap('<a href="'+url+'"></a>');
        });
    </script>
    @endif
@endsection


