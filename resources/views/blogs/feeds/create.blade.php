@extends('master')

@section('page_title')
Add New Blog Feed
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Add New Blog Feed</strong></h4>
        <p class="card-category">Add blog feed to auto blog</p>
    </div>
    <div class="card-body">
        <form action="{{ route('blogs.feeds.create', Session::get('subdomain')) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Rss Feed URL <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter RSS Feed URL.">info</i></label>
                        <input type="text" name="url" value="{{ old('url') }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Category <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose category to this feed">info</i></label>
                        <select name="category" id="" class="form-control">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == old('category') ? 'selected' : ''}}>{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Product Category <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose product category to get products for the blog feed.">info</i></label>
                        <select name="product_category" id="" class="form-control">
                            @foreach($productCategories as $productCategory)
                            <option value="{{ $productCategory->id }}" {{ $productCategory->id == old('product_category') ? 'selected' : ''}}>{{ $productCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Automation Settings</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">From</label>
                        <input type="date" name="from" value="{{ old('from') }}" class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">To</label>
                        <input type="date" name="to" value="{{ old('to') }}" class="form-control" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Frequency</label>
                        <select name="frequency" id="" class="form-control">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="auto_publish">
                            Enable Auto Publish
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Save blog feed details">info</i></button>
            <button type="submit" class="btn btn-primary" name="btn_save_feed">Save and Feed <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Save blog feed details and fetch feeds from the URL">info</i></button>
            <a href="{{ route('blogs.feeds.index', Session::get('subdomain')) }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script>
        $('[data-toggle="tooltip"]').tooltip({ html: true })
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
