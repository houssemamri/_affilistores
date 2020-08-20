@extends('master')

@section('content')
<div class="row">
    @if($membership->frequency == 1)
        @php $frequency = 'Month' @endphp
    @elseif($membership->frequency == 3)
        @php $frequency = 'Quarter' @endphp
    @elseif($membership->frequency == 12)
        @php $frequency = 'Year' @endphp
    @elseif($membership->frequency == 120)
        @php $frequency = 'Lifetime' @endphp
    @endif
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title"><strong> {{ $membership->title }} </strong></h4>
                <p class="card-category"><strong>$ {{ ($membership->frequency == 'lifetime') ? $membership->product_price : $membership->product_price .'/'. $frequency }}</strong></p>
            </div>
            <div class="card-body">
                <ul>
                    @foreach($membership->features as $features)
                    <li>{{ $features->feature }}</li>
                    @endforeach
                </ul>

                <a href="#" class="btn btn-block btn-primary {{ Auth::user()->memberDetail->membership_id == $membership->id ? 'disabled' : '' }}">{{ Auth::user()->memberDetail->membership_id == $membership->id ? 'Current Membership' : 'Upgrade Membership' }}</a>
            </div>
        </div>
    </div>

    @if(isset($nextMembership))
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title"><strong> {{ $nextMembership->title }} </strong></h4>
                <p class="card-category"><strong>$ {{ ($nextMembership->frequency == 'lifetime') ? $nextMembership->product_price : $nextMembership->product_price .'/'. $frequency }}</strong></p>
            </div>
            <div class="card-body">
                <ul>
                    @foreach($nextMembership->features as $features)
                    <li>{{ $features->feature }}</li>
                    @endforeach
                </ul>

                <a href="{{ isset($nextMembership->upgrade_membership_url) && !empty($nextMembership->upgrade_membership_url) ? $nextMembership->upgrade_membership_url : '#' }}" class="btn btn-block btn-primary {{ Auth::user()->memberDetail->membership_id == $nextMembership->id ? 'disabled' : '' }}">{{ Auth::user()->memberDetail->membership_id == $nextMembership->id ? 'Current Membership' : 'Upgrade Membership' }}</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection