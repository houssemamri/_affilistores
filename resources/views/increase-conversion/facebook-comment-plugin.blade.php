@extends('master')

@section('page_title')
Facebook Comment Plugin
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Facebook Comment Plugin</strong></h4>
        <p class="card-category">Add Facebook Comment plugin to your store</p>
    </div>
  <div class="card-body">
    <form action="{{ route('conversions.commentPlugin', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-group">
                        <label class="bmd-label-floating">Code SDK <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Paste Javascript SDK code from Facebook to integrate Facebook Comment Plugin">info</i></label>
                        <textarea class="form-control terms" name="code_sdk" rows="8" cols="10" placeholder="Enter Javascript SDK Code snippet from Facebook" id="editor-terms">{{ ($comment->sdk_code) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-group">
                        <label class="bmd-label-floating">Code Snippet <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Paste code snippet from Facebook to integrate Facebook Comment Plugin">info</i></label>
                        <textarea class="form-control terms" name="code_snippet" rows="5" cols="10" placeholder="Enter Code Snippet from Facebook" id="editor-terms">{{ ($comment->code_snippet) }}</textarea>
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
$('[data-toggle="tooltip"]').tooltip()
</script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
