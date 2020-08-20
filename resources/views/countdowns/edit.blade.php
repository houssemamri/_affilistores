
@extends('master')

@section('page_title')
Countdown Timer
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong>Countdown Timer </strong></h4>
        <p class="card-category">Create your product countdown timer</p>
    </div>
    <div class="card-body">
        <form action="{{  route('countdowns.edit', ['subdomain' => Session::get('subdomain'), 'id' => $id]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="20%"><strong>Name <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add name of your Countdown timer. Maximum length 80 characters">info</i></strong></th>
                                    <td>
                                        <input type="text" name="name" maxlength="80" placeholder="Enter Countdown Timer Name" class="form-control" value="{{ $countdown->name }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%"><strong>Product Page <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Select product page to show the countdown timer.">info</i></strong></th>
                                    <td>
                                       <select name="product" class="form-control">
                                           @foreach($products as $product)
                                           <option value="{{ $product->id }}" {{ $countdown->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                           @endforeach
                                       </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%"><strong>Description <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add description to your countdown timer. Maximum length 350 characters">info</i></strong></th>
                                    <td>
                                        <textarea name="description" maxlength="350" id="" class="form-control" rows="5" required>{{ $countdown->description }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="25%"><strong>Countdown Date <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set your countdown date.">info</i></strong></th>
                                    <td>
                                    <input type="date" name="countdown_date" placeholder="Enter Countdown Timer Name" value="{{ date_format(date_create($countdown->countdown_date), 'Y-m-d') }}" min="{{ date('Y-m-d', strtotime(date('Y-m-d') . '+ 1 day')) }}" class="form-control" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%"><strong>Access Link <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set access link of your coundwon timer">info</i></strong></th>
                                    <td>
                                        <input type="url" name="access_link" placeholder="Enter Access Link" value="{{ $countdown->access_link }}" class="form-control" required>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong>Settings</strong></h4>
                            <p class="card-category">Configure settings for your social proof</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th width="20%"><strong>Background Color <i class="material-icons tip" data-toggle="tooltip" min="1" data-placement="right" title="Set background color of countdown timer">info</i></strong></th>
                                            <td>
                                                <input type="color" value="{{ json_decode($countdown->settings)->background_color }}" name="background_color" class="form-control" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="20%"><strong>Text color<i class="material-icons tip" data-toggle="tooltip" min="1" data-placement="right" title="Set description text color">info</i></strong></th>
                                            <td>
                                                <input type="color" value="{{ json_decode($countdown->settings)->color }}" name="text_color" placeholder="Enter Text color" class="form-control" required>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{  route('countdowns.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
                <div class="clearfix"></div>
            </div>
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