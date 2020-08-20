@if(isset($countdownTimer) && $countdownTimer->countdown_date >= date('Y-m-d H:i:s'))
  <div class="fixed-footer" id="fixed-footer">
      <button type="button" class="close" data-target="#fixed-footer" data-dismiss="alert"> <span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      <div class="custom-container">
          <div class="row">
              <div class="col-xl-6 col-lg-6 col-sm-12 timer-description">
                  <p>{{ $countdownTimer->description }}</p>
              </div>
              <div class="col-xl-6 col-lg-6 col-sm-12 timer">
                  <div class="clock"></div>
              </div>
          </div>
      </div>
  </div>
@endif
@if(isset($exitpop))
<div class="modal fade" id="exitpop" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center" id="exampleModalLongTitle"><strong class="heading"> {{ $exitpop->heading }} </strong></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <form action="{{ route('index.send.exitpopemail', ['subdomain' => $store->subdomain]) }}" method="post">
          {!! csrf_field() !!}

          <img src="{{ $exitpop->image }}" alt="" id="image" class="img-fluid">

          <p class="text-center"><input type="email" name="email" class="form-control input-block text-center" placeholder="Enter your email address" required></p>
          <p class="text-center body">{{ $exitpop->body }}</p>

          <div class="">
            <button class="btn btn-block btn-primary btn-text" type="submit">{{ $exitpop->button_text }}</button>
          </div>
        </form>
    </div>
  </div>
</div>
@endif