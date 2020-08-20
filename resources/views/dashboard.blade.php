@extends('master')

@section('page_title')
    Dashboard
@endsection

@section('content')
<div class="row">
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body text-center">
                    <p><strong>Products Posted <br>Today</strong></p>
                    <span class="badge badge-dark-blue stats-number">{{ $statistics['postedToday'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body text-center">
                <p><strong>Total Posted <br>Products</strong></p>
                <span class="badge badge-dark-blue stats-number">{{ $statistics['totalPosted'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body text-center">
                    <p><strong>Total Posted <br>Reviews</strong></p>
                    <span class="badge badge-dark-blue stats-number">{{ $statistics['postedReviews'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body text-center">
                    <p><strong>Total Posted <br>Blogs</strong></p>
                    <span class="badge badge-dark-blue stats-number">{{ $store->blogs->where('published', 1)->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body text-center">
                <p><strong>Total Product <br>Clicks This Week</strong></p>
                <span class="badge badge-dark-blue stats-number">{{ $statistics['weeklyHits'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body text-center">
                <p><strong>Total Affiliate Clicks <br>This Week</strong></p>
                <span class="badge badge-dark-blue stats-number">{{ $statistics['weeklyAffiliateHits'] }}</span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card card-stats">
            <div class="card-header card-header-default card-header-icon card-welcome">
                <div class="card-icon card-header-light-blue">
                    <i class="material-icons">message</i>
                </div>
                <p class="card-category"> Current Store </p>
                <h3 class="card-title">
                    Welcome to {{ ucwords($store->name) }}
                </h3>
            </div>
        </div>

        <div class="card card-stats">
            <div class="card-header card-header-default card-header-icon">
                <div class="card-icon card-header-light-blue">
                    <i class="material-icons">store</i>
                </div>
                <p class="card-category">Visit Your Stores</p>
                <h3 class="card-title">Store Shortcuts</h3>
            </div>
            <div class="card-body stores xscroll">
                <table class="table">
                    <tbody>
                        @foreach($stores as $otherStore)
                        <tr class="list-store" onclick="goto('{{ route('index', ['subdomain' => $otherStore->subdomain]) }}')">
                            <td>{{ $otherStore->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-6">
        <div class="row">
            <div class="col-lg-12">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="{{ str_replace('watch?v=', 'embed/', 'https://www.youtube.com/watch?v=SOVa7VB5_V0414') }}" allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-sm-12">
        <div class="row">
        <div class="col-lg-4 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-default card-header-icon">
                    <div class="card-icon card-header-light-blue">
                        <i class="material-icons">mail</i>
                    </div>
                    <p class="card-category">Contact Us Messages</p>
                    <h3 class="card-title">Recent Messages</h3>
                </div>
                <div class="card-footer table-responsive">
                    <table class="table table-fixed">
                        <tbody>
                            @foreach($messages as $message)
                            <tr>
                                <td><strong>{{ $message->subject }} </strong><span class="text-muted">@ {{ date_format($message->created_at, 'F d, Y H:i a') }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-stats">
                <div class="card-header card-header-default card-header-icon">
                    <div class="card-icon card-header-light-blue">
                        <i class="material-icons">store</i>
                    </div>
                    <p class="card-category">Inspirational stores</p>
                    <h3 class="card-title">Featured Stores</h3>
                </div>
                <div class="card-body table-responsive stores xscroll">
                    <table class="table table-fixed">
                        <tbody>
                            @foreach($featuredStores as $featuredStore)
                            <tr>
                                <td><strong><a target="_blank" href="{{ route('index', $featuredStore->subdomain) }}">{{ $featuredStore->name }}</a> </strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-default card-header-icon">
                    <div class="card-icon card-header-light-blue">
                        <i class="material-icons">library_books</i>
                    </div>
                    <p class="card-category">Articles</p>
                    <h3 class="card-title">Read some articles</h3>
                </div>
                <div class="card-footer ">
                    <table class="table">
                        <tbody>
                            @foreach($articles as $article)
                            <tr class="list-store" onclick="goto('{{ route('articles.read', ['subdomain' => $store->subdomain, 'id' => Crypt::encrypt($article->id)]) }}')">
                                <td>{{ $article->title }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-default card-header-icon card-welcome">
                    <div class="card-icon card-header-light-blue">
                        <i class="material-icons">store</i>
                    </div>
                    <p class="card-category"> Total number of store created this month </p>
                    <h3 class="card-title">{{ $countStoreThisMonth }} Stores
                        @if($limit == 1000)
                        / Unlimited
                        @else
                        / {{ $limit }}
                        @endif
                    </h3>
                </div>
            </div>
            @if(isset($poll))
            <div class="card card-stats">
                <div class="card-header card-header-default card-header-icon">
                    <div class="card-icon card-header-light-blue">
                        <i class="material-icons">poll</i>
                    </div>
                    <p class="card-category">{{ date_format($poll->created_at, 'F d, Y') }}</p>
                    <h3 class="card-title">
                        {{ $poll->question }}
                    </h3>
                </div>
                <hr>

                @if($pollVoted == 0)
                <div class="poll-option">
                    <div class="card-footer">
                        @foreach($poll->options as $option)
                        <div class="form-check form-check-radio">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="option" value="{{ Crypt::encrypt($option->id) }}" data-id="{{ $loop->iteration }}">
                                {{ $option->name }}
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div><br>
                        @endforeach
                    </div>
                    
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary btn-block btn-vote">Vote</button>
                    </div>
                    <hr>
                </div>
                @endif
                
                <div class="card-header card-header-warning text-left card-header-icon">
                    <h3 class="card-title">
                        Poll Results
                    </h3>
                </div>

                <div class="card-footer poll-results">
                    <div class="initial-results">
                        @foreach($poll->options as $option)
                        <div class="row ">
                            <div class="col-lg-12">
                                <h4><strong>{{ $option->name }}</strong> ( {{ count($option->votes) }} ) </h4>
                                <div class="progress poll-option-{{ $loop->iteration }}">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($poll->total_vote == 0) ? 0 : (count($option->votes) / $poll->total_vote) * 100 }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                    <small class="justify-content-center d-flex">{{ ($poll->total_vote == 0) ? 0 : number_format((count($option->votes) / $poll->total_vote) * 100, 2, '.', '') }}%</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="total-vote"><strong>Total Votes: </strong> {{ $poll->total_vote }}</h4>
                        </div>
                    </div>
                </div>          
            </div>
            @endif
        </div>
        </div>
    </div>

    
</div>
@endsection

@section('alert')
  @include('extra.alerts')
@endsection

@section('custom-scripts')
    <script src="{!! asset('js/axios.min.js') !!}"></script>
    <script>
        function goto(url) {
            window.open(url, '_blank');
        }
    </script>
    @if(isset($poll))
    <script>
        $('.btn-vote').on('click', function(){
            var option = $("input[name='option']:checked").val();
            var dataId = $("input[name='option']:checked").attr('data-id');
            var url = '{{ route("poll.vote", ["subdomain" => Session::get("subdomain"), "pollId" => Crypt::encrypt($poll->id), "option" => "pollOptionId"]) }}';
            url = url.replace('pollOptionId', option);
           
            axios.get(url)
            .then(function (response) {
                var total_vote = response.data.total;
                var options = response.data.options;
                var html = '';

                $.each(options, function(index, option){
                    var percentage = total_vote == 0 ? 0 : (option.total / total_vote) * 100;
                    html +=' <div class="row">';
                    html +='    <div class="col-lg-12">';
                    html +='        <h4><strong>'+ option.name +'</strong> ( '+ option.total +' ) </h4>';
                    html +='        <div class="progress poll-option-'+ (index + 1) +'">';
                    html +='            <div class="progress-bar bg-info" role="progressbar" style="width: '+ percentage +'%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">';
                    html +='            <small class="justify-content-center d-flex">'+ percentage +'%</small>';
                    html +='            </div>';
                    html +='        </div>';
                    html +='    </div>';
                    html +=' </div>';
                });
                
                $('.poll-option').remove();
                $('.initial-results').empty();
                $('.initial-results').append(html);
                $('.total-vote').html('<strong>Total Vote: </strong> ' + total_vote)
               
                $.notify({
                    icon: "info",
                    message: 'Thank you for voting!',
                },{
                    type: 'info'
                });
            })
            .catch(function (error) {
                console.log(error);
            });
        });
    </script>
    @endif
@endsection



