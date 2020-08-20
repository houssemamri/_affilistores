@extends('master')

@section('page_title')
    Bonuses
@endsection

@section('content')
<div class="row">
    @if(count($bonuses) == 0)
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body text-center">
                <h4><strong>No Bonuses Found</strong></h4>
            </div>
        </div>
    </div>

    @else
        @foreach($bonuses as $bonus)
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <img src="{{ asset('bonuses/' . $bonus->bonus->image) }}" alt="" class="img-fluid">
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <h4 class="card-title"><strong> {{ $bonus->bonus->name }} </strong></h4>
                        <hr>
                        {!! strlen($bonus->bonus->description) > 380 ? substr($bonus->bonus->description, 0, 377) . "..." : $bonus->bonus->description !!}
                        <p><strong>{{ $bonus->size }}</strong></p>
                        <hr>
                        <a href="{{ asset('bonuses/' . $bonus->bonus->file) }}" class="btn btn-default" download>Download</a>
                    </div>
                </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection

@section('custom-scripts')
  
@endsection


