<h3 class="text-uppercase bold">Customer Reviews</h3>
<hr>
<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="card-body">
            <ul class="nav nav-pills flex-column nav-box">
                <li class="nav-item">
                    <a class="nav-link active" href="#reviews" data-toggle="tab">Reviews</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#addReview" data-toggle="tab">Add Review</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-8 col-md-12">
        <div class="card mt-0 card-plain">
            <div class="card-body ">
                <div class="tab-content text-center">
                    <div class="tab-pane active" id="reviews">
                        <div class="">
                            <div class="card-body">
                                <h4><strong>Customer reviews</strong></h4>
                                <h5><strong>Average Customer Review</strong></h5>
                                @php
                                    $totalReview = count($product->reviews->where('approved', 1));
                                    $totalRatings = $product->reviews->where('approved', 1)->sum('ratings');
                                    $averageRatings = $totalReview == 0 ? 0 : $totalRatings / $totalReview;
                                @endphp
                                
                                @if($averageRatings > 0)
                                    <select id="average">
                                        <option value="1" {{ ceil($averageRatings) == 1 ? 'selected' : ''}}>1</option>
                                        <option value="2" {{ ceil($averageRatings) == 2 ? 'selected' : ''}}>2</option>
                                        <option value="3" {{ ceil($averageRatings) == 3 ? 'selected' : ''}}>3</option>
                                        <option value="4" {{ ceil($averageRatings) == 4 ? 'selected' : ''}}>4</option>
                                        <option value="5" {{ ceil($averageRatings) == 5 ? 'selected' : ''}}>5</option>
                                    </select>
                                @endif

                                <p><strong>{{ $totalReview }} customer review/s</strong></p>
                                <hr>
                                @foreach($product->reviews->where('approved', 1)->take(4) as $review)
                                <select class="list-ratings">
                                    <option value="1" {{ $review->ratings == 1 ? 'selected' : ''}}>1</option>
                                    <option value="2" {{ $review->ratings == 2 ? 'selected' : ''}}>2</option>
                                    <option value="3" {{ $review->ratings == 3 ? 'selected' : ''}}>3</option>
                                    <option value="4" {{ $review->ratings == 4 ? 'selected' : ''}}>4</option>
                                    <option value="5" {{ $review->ratings == 5 ? 'selected' : ''}}>5</option>
                                </select>

                                <span class="text-muted"><small>{{ date_format($review->created_at, 'F d, Y') }}</small></span>

                                <p>
                                    <strong>By</strong> {{ $review->name }} 
                                </p>
                                
                                <p>
                                    {{ $review->review }}
                                </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="addReview">
                        <div class="">
                            <div class="card-body text-left">
                                <div class="row product-reviews">
                                    <div class="col-lg-12">
                                        <h3>Add a product review</h3>
                                        <form action="{{ route('index.product.review', Session::get('subdomain')) }}" method="POST">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="permalink" value="{{ $product->permalink }}">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="bmd-label-floating">Rate this product</label>
                                                        <select id="ratings" name="ratings">
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="bmd-label-floating">Name</label>
                                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" >
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="bmd-label-floating">Email</label>
                                                    <input type="text" name="email" value="{{ old('email') }}" class="form-control" >
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="bmd-label-floating">Review</label>
                                                        <textarea name="review" id=""  rows="6" class="form-control">{{ old('review') }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <div class="g-recaptcha" data-sitekey="6LevDWAUAAAAAMzw-vrH-fgbw4UGie1NdqaBNsFl"></div>
                                                    </div>
                                                </div>


                                                <div class="col-lg-12">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


