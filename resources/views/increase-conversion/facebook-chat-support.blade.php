@extends('master')

@section('page_title')
Facebook Customer Chat
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Facebook Customer Chat </strong></h4>
        <p class="card-category">Add Facebook Customer Chat plugin to your store</p>
    </div>
  <div class="card-body">
    <form action="{{ route('conversions.customerChat', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-group">
                        <label class="bmd-label-floating">Code Snippet <i class="material-icons tip" data-toggle="popover" data-placement="right" data-content="Paste code snippet from Facebook to integrate Facebook Customer Chat. Here is a guide to setup yout customer chat plugin https://developers.facebook.com/docs/messenger-platform/discovery/customer-chat-plugin/#steps">info</i></label>
                        <textarea class="form-control terms" name="code_snippet" rows="20" cols="10" placeholder="Enter Code Snippet from Facebook" id="editor-terms">{{ ($chat->code) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" name="btn_terms">Save</button>
        <div class="clearfix"></div>
    </form>
  </div>
</div>
@endsection

@section('custom-scripts')
<script>
    $('[data-toggle="popover"]').popover()
</script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
