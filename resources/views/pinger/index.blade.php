@extends('master')

@section('page_title')
Pinger Service
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Ping new product </strong></h4>
        <p class="card-category">Ping product to improve SEO ranking</p>
    </div>
    <div class="card-body">
        <form action="{{ route('pinger.index', Session::get('subdomain')) }}" method="POST">
            {!! csrf_field() !!}

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Category</label>
                        <select name="category" id="" class="form-control">
                            <option value="">Select a Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Product To Ping</label>
                        <select name="productToPing" id="" class="form-control" disabled>
                           
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Ping</button>
            <div class="clearfix"></div>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script>

        $('select[name=category]').change(function(){
            if(!$(this).val())
                return

            var category = $(this);
            var products = $('select[name=productToPing]');
            var url = "{{ route('pinger.products', ['subdomain' => Session::get('subdomain'), 'id' => 'categoryId' ]) }}";
            url = url.replace('categoryId', category.find(':selected').val());
            
            category.attr('disabled', '');
            products.attr('disabled', '');

            axios.get(url)
                .then(function(response){
                    console.log(response);
                    var html = '';

                    $.each(response.data, function(index, product){
                        html += '<option value="'+ product.id +'">'+ product.name +'</option>';
                    })

                    products.empty();
                    products.append(html);

                    category.removeAttr('disabled');
                    products.removeAttr('disabled');
                })
                .catch(function(error){
                    console.log(error)
                    
                    category.removeAttr('disabled');
                    products.removeAttr('disabled');
                })
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
