@extends('master')

@section('custom-css')
<link rel="stylesheet" href="{!! asset('css/tagify.css') !!}">
@endsection

@section('page_title')
Automations
@endsection

@section('content')

<form action="{{ route('automation.edit', ['subdomain' => Session::get('subdomain'), 'id' => $id]) }}" method="post" id="automation-form">
    {{ csrf_field() }}
    <div class="card ">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> Set automation settings </strong></h4>
            <p class="card-category">Enter all required fields</p>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table member-table " id="list">
                    <tbody>
                        <tr>
                            <th>Source <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose eCommerce store to get your products">info</i></th>
                            <td colspan=3>
                                <select name="source" id="source" class="form-control">
                                    @if(in_array('amazon', $features))
                                    <option value="amazon" {{ $stores['amazon'] ? '' : 'disabled'}} {{ $automation->source == 'amazon' ? 'selected' : '' }}>Amazon {{ $stores['amazon'] ? '' : '(Please set affiliate settings for Amazon)'}}</option>
                                    @endif
                                    @if(in_array('ebay', $features))
                                    <option value="ebay" {{ $stores['ebay'] ? '' : 'disabled'}} {{ $automation->source == 'ebay' ? 'selected' : '' }}>Ebay {{ $stores['ebay'] ? '' : '(Please set affiliate settings for Ebay)'}}</option>
                                    @endif
                                    @if(in_array('aliexpress', $features))
                                    <option value="aliexpress" {{ $stores['aliexpress'] ? '' : 'disabled'}} {{ $automation->source == 'aliexpress' ? 'selected' : '' }}>AliExpress {{ $stores['aliexpress'] ? '' : '(Please set affiliate settings for AliExpress)'}}</option>
                                    @endif
                                    @if(in_array('walmart', $features))
                                    <option value="walmart" {{ $stores['walmart'] ? '' : 'disabled'}} {{ $automation->source == 'walmart' ? 'selected' : '' }}>Walmart {{ $stores['walmart'] ? '' : '(Please set affiliate settings for Walmart)'}}</option>
                                    @endif
                                    @if(in_array('shopcom', $features))
                                    <option value="shopcom" {{ $stores['shopcom'] ? '' : 'disabled'}} {{ $automation->source == 'shopcom' ? 'selected' : '' }}>Shop.com {{ $stores['shopcom'] ? '' : '(Please set affiliate settings for Shop.com)'}}</option>
                                    @endif
                                    @if(in_array('cjcom', $features))
                                    <option value="cjcom" {{ $stores['cjcom'] ? '' : 'disabled'}} {{ $automation->source == 'cjcom' ? 'selected' : '' }}>Cj.com {{ $stores['cjcom'] ? '' : '(Please set affiliate settings for Cj.com)'}}</option>
                                    @endif
                                    @if(in_array('jvzoo', $features))
                                    <option value="jvzoo" {{ $stores['jvzoo'] ? '' : 'disabled'}} {{ $automation->source == 'jvzoo' ? 'selected' : '' }}>JVZoo {{ $stores['jvzoo'] ? '' : '(Please set affiliate settings for JVZoo)'}}</option>
                                    @endif
                                    @if(in_array('clickbank', $features))
                                    <option value="clickbank" {{ $stores['clickbank'] ? '' : 'disabled'}} {{ $automation->source == 'clickbank' ? 'selected' : '' }}>ClickBank {{ $stores['clickbank'] ? '' : '(Please set affiliate settings for ClickBank)'}}</option>
                                    @endif
                                    @if(in_array('warriorplus', $features))
                                    <option value="warriorplus" {{ $automation->source == 'warriorplus' ? 'selected' : '' }}>Warrior Plus</option>
                                    @endif
                                    @if(in_array('paydotcom', $features))
                                    <option value="paydotcom" {{ $automation->source == 'paydotcom' ? 'selected' : '' }}>PayDotCom</option>
                                    @endif

                                   
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Select Category <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose category of products to search">info</i></th>
                            <td colspan=3>
                                <select name="category" id="searchIndex" class="form-control">
                                    @if($automation->source == 'amazon')
                                        @foreach($searchIndex as $key => $index)
                                        <option value="{{ $key }}" {{ $automation->category == $key ? 'selected' : ''}}>{!! $index !!}</option>
                                        @endforeach
                                    @elseif($automation->source == 'ebay')
                                        @foreach($searchIndex as $key => $index)
                                        <option value="{{ $key }}" {{ $automation->category == $key ? 'selected' : ''}}>{!! $key !!}</option>
                                        @endforeach 
                                    @elseif($automation->source == 'aliexpress')
                                        @foreach($searchIndex as $key => $index)
                                        <option value="{{ $key }}" {{ $automation->category == $key ? 'selected' : ''}}>{!! $key !!}</option>
                                        @endforeach 
                                    @elseif($automation->source == 'walmart')
                                        @foreach($searchIndex as $key => $index)
                                        <option value="{{ $key }}" {{ $automation->category == $key ? 'selected' : ''}}>{!! $key !!}</option>
                                        @endforeach 
                                    @elseif($automation->source == 'shopcom')
                                        @foreach($searchIndex as $key => $index)
                                        <option value="{{ $key }}" {{ $automation->category == $key ? 'selected' : ''}}>{!! $key !!}</option>
                                        @endforeach 
                                    @elseif($automation->source == 'cjcom')
                                        @foreach($searchIndex as $key => $index)
                                        <option value="{{ $key }}" {{ $automation->category == $key ? 'selected' : ''}}>{!! $key !!}</option>
                                        @endforeach 
                                    @endif()
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Keyword <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter keywords of products to search">info</i></th>
                            <td colspan=3>
                                <input type="text" id="keyword" name="keyword" value="{{ $automation->keyword }}" placeholder="Enter Keyword" class="form-control" required>
                            </td>
                        </tr>
                        <tr>
                            <th>No. of Daily Posts <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set number of products to fetch. Max 5 products">info</i></th>
                            <td colspan=3>
                                <input type="number" name="number_of_daily_post" value="{{ $automation->number_daily_post }}" class="form-control" min="1" max="5" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Automation Start Date <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set when automation will start">info</i></th>
                            <td>
                                <input type="date" name="start_date" min="{{ date('Y-m-d') }}" max="{{ $automation->end_date }}" value="{{ $automation->start_date }}" class="form-control" required>
                            </td>
                            <th class="text-center">Automation End Date <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set when automation will end">info</i></th>
                            <td>
                                <input type="date" name="end_date" min="{{ $automation->start_date }}" value="{{ $automation->end_date }}" class="form-control"  required>
                            </td>
                        </tr>   
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title"><strong> Choose Your Category & Tag </strong></h4>
            <p class="card-category">Set Categories and Tags</p>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="col-lg-12">
                    <ul class="option-list">
                        @foreach($categories as $category)
                        
                            @if(in_array($category->id, explode(',', json_decode($automation->product_data)->categories)))
                            <li>
                                <span class="badge badge-warning" data-name="{{ $category->name }}">
                                    {{ $category->name }}
                                    <span class="remove-option" onclick="remove(this);">X</span>  
                                </span>
                                <input type="hidden" name="categories[]" value="{{ $category->id }}">
                            </li>
                            @endif
                        @endforeach 
                    </ul>
                </div>
                <label class="bmd-label-floating">Category <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Choose category to link to your product. Can select multiple categories">info</i></label>
                <select name="" id="product_category" class="form-control">
                    <option value=""></option>
                    @foreach($categories as $category)
                        @if(!in_array($category->id, explode(',', json_decode($automation->product_data)->categories)))
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="bmd-label-floating">Tags <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter product tags to link to your product">info</i></label>
                <input type="text" name="tags" placeholder="Enter Tags" value="{{ implode(',', $tags) }}">
            </div>
            <div class="form-group">
                <label class="bmd-label-floating">Publish Date <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set schedule date to publish your product">info</i></label>
                <input type="date" id="published_date" name="published_date" min="{{ date('Y-m-d') }}" value="{{ json_decode($automation->product_data)->published_date }}" class="form-control" required>
            </div>
            <button class="btn btn-primary btn-publish" type="submit">Save</button>
            <a href="{{ route('automation.index', Session::get('subdomain')) }}" class="btn btn-danger">Cancel</a>
        </div>
    </div>
</form>

@endsection

@section('custom-scripts')
    <script src="{!! asset('js/jquery.dataTables.min.js') !!}"></script>
    <script src="{!! asset('js/dataTables.bootstrap4.min.js') !!}"></script>
    <script src="{!! asset('js/axios.min.js') !!}"></script>
    <script src="{!! asset('js/dataTables.checkboxes.min.js') !!}"></script>
    <script src="{!! asset('js/tagify.min.js') !!}"></script>
    
    <script>
        var categories = '{!! $searchIndices !!}';

        $('[data-toggle="tooltip"]').tooltip()
        $('[name=tags]').tagify();
        $('[name=tags]').tagify({duplicates : false});

        function amazonOptions(){
            var options = '';
            var amazon = JSON.parse(categories).amazon;
            
            $(amazon).each(function(index, value){
                options += '<option value="' + value + '">'+ value +' </option>';
            })    

            return options;
        }

        function ebayOptions(){
            var options = '';
            var ebay = JSON.parse(categories).ebay;

            $.map(ebay, function(value, index) {
                options += '<option value="' + index + '">'+ index +' </option>';
            });

            return options;
        }

        function aliexpressOptions(){
            var options = '';
            var aliexpress = JSON.parse(categories).aliexpress;

            $.map(aliexpress, function(value, index) {
                options += '<option value="' + index + '">'+ index +' </option>';
            });

            return options;
        }

        function walmartOptions(){
            var options = '';
            var walmart = JSON.parse(categories).walmart;

            $.map(walmart, function(value, index) {
                options += '<option value="' + index + '">'+ index +' </option>';
            });

            return options;
        }
        
        function shopcomOptions(){
            var options = '';
            var schopcom = JSON.parse(categories).shopcom;

            $.map(schopcom, function(value, index) {
                options += '<option value="' + index + '">'+ index +' </option>';
            });

            return options;
        }

        function cjcomOptions(){
            var options = '';
            var cjcom = JSON.parse(categories).cjcom;

            $.map(cjcom, function(value, index) {
                options += '<option value="' + value + '">'+ index +' </option>';
            });

            return options;
        }

        function jvzooOptions(){
            var options = '';
            var jvzoo = JSON.parse(categories).jvzoo;

            $.map(jvzoo, function(value, index) {
                options += '<option value="' + value + '">'+ index +' </option>';
            });

            return options;
        }

        function clickBankOptions(){
            var options = '';
            var clickbank = JSON.parse(categories).clickbank;

            $.map(clickbank, function(value, index) {
                options += '<option value="' + value + '">'+ index +' </option>';
            });

            return options;
        }

        function warriorPlusOptions(){
            var options = '';
            var warriorplus = JSON.parse(categories).warriorplus;

            $.map(warriorplus, function(value, index) {
                options += '<option value="' + value + '">'+ index +' </option>';
            });

            return options;
        }

        function payDotComOptions(){
            var options = '';
            var paydotcom = JSON.parse(categories).paydotcom;

            $.map(paydotcom, function(value, index) {
                options += '<option value="' + value + '">'+ index +' </option>';
            });

            return options;
        }

        function remove(element){
            var option = $(element);
            var optionHtml = '<option value="'+$(element).parent().next('input').val()+'">'+$(element).parent().attr('data-name')+'</option>';

            $('select#product_category').append(optionHtml);
            option.parent().parent().remove();
        }

        $('select#product_category').change(function(){
            var option = $(this).find(':selected');

            var optionHtml = '<li>';
            optionHtml += '<span class="badge badge-warning" data-name="'+option.text()+'">'+option.text()+' <span class="remove-option" onclick="remove(this);">X</span>  </span>';
            optionHtml += '<input type="hidden" name="categories[]" value="'+option.val()+'">';
            optionHtml += '</li>';
            
            $('.option-list').append(optionHtml);
            option.remove();
        });
        
        var source = $('#source').val();
        if(source == 'amazon'){
            $('#searchIndex').empty();
            $('#searchIndex').append(amazonOptions);
        }else if(source == 'ebay'){
            $('#searchIndex').empty();
            $('#searchIndex').append(ebayOptions);
        }else if(source == 'aliexpress'){
            $('#searchIndex').empty();
            $('#searchIndex').append(aliexpressOptions);
        }else if(source == 'walmart'){
            $('#searchIndex').empty();
            $('#searchIndex').append(walmartOptions);
        }else if(source == 'shopcom'){
            $('#searchIndex').empty();
            $('#searchIndex').append(shopcomOptions);
        }else if(source == 'cjcom'){
            $('#searchIndex').empty();
            $('#searchIndex').append(cjcomOptions);
        }else if(source == 'jvzoo'){
            $('#searchIndex').empty();
            $('#searchIndex').append(jvzooOptions);
        }else if(source == 'clickbank'){
            $('#searchIndex').empty();
            $('#searchIndex').append(clickBankOptions);
        }else if(source == 'warriorplus'){
            $('#searchIndex').empty();
            $('#searchIndex').append(warriorPlusOptions);
        }else if(source == 'paydotcom'){
            $('#searchIndex').empty();
            $('#searchIndex').append(payDotComOptions);
        }

        $('#source').change(function(){
            var option = $(this).find(':selected');
            
            if(option.val() == 'amazon'){
                $('#searchIndex').empty();
                $('#searchIndex').append(amazonOptions);
            }else if(option.val() == 'ebay'){
                $('#searchIndex').empty();
                $('#searchIndex').append(ebayOptions);
            }else if(option.val() == 'aliexpress'){
                $('#searchIndex').empty();
                $('#searchIndex').append(aliexpressOptions, 'aliexpress');
            }else if(option.val() == 'walmart'){
                $('#searchIndex').empty();
                $('#searchIndex').append(walmartOptions, 'walmart');
            }else if(option.val() == 'shopcom'){
                $('#searchIndex').empty();
                $('#searchIndex').append(shopcomOptions, 'shopcom');
            }else if(option.val() == 'cjcom'){
                $('#searchIndex').empty();
                $('#searchIndex').append(cjcomOptions, 'cjcom');
            }else if(option.val() == 'jvzoo'){
                $('#searchIndex').empty();
                $('#searchIndex').append(jvzooOptions, 'jvzoo');
            }else if(option.val() == 'clickbank'){
                $('#searchIndex').empty();
                $('#searchIndex').append(clickBankOptions, 'clickbank');
            }else if(option.val() == 'warriorplus'){
                $('#searchIndex').empty();
                $('#searchIndex').append(warriorPlusOptions, 'warriorplus');
            }else if(option.val() == 'paydotcom'){
                $('#searchIndex').empty();
                $('#searchIndex').append(payDotComOptions, 'paydotcom');
            } 
        });

        $('input[name="start_date"]').change(function(){
            $('input[name="end_date"]').attr('min', $(this).val())
        })

        $('input[name="end_date"]').change(function(){
            $('input[name="start_date"]').attr('max', $(this).val())
        })

        $('input[type="radio"]').change(function(){
            if($(this).attr('id') == 'now'){
                $('.published-date').hide();
                $('.published-date').removeAttr('required');
            }else{
                $('.published-date').show();
                $('.published-date').attr('required');
            }
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
