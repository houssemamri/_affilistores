@extends('admin.master')

@section('page_title')
Memberships
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('memberships.add') }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Add New Membership </strong></h4>
                            <p class="card-category">Create new membership, enter details for membership</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Title</label>
                                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">JVZoo Product Id</label>
                                    <input type="text" name="jvzoo_product_id" value="{{ old('jvzoo_product_id') }}" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Upgrade Membership URL</label>
                                    <input type="text" name="upgrade_membership_url" value="{{ old('upgrade_membership_url') }}" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Next Upgrade Membership</label>
                                        <select name="next_upgrade_membership_id" class="form-control">
                                            @foreach($memberships as $membership)
                                            <option value="{{ $membership->id }}">{{ $membership->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Product Price</label>
                                        <input type="number" step="any" name="product_price" value="{{ old('product_price') }}" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">No. of Stores Per Month</label>
                                        <select name="stores_per_month" id="" class="form-control">
                                            <option value="5" {{ old('stores_per_month') == '5' ? 'selected' : '' }}>5 Stores / Month</option>
                                            <option value="10" {{ old('stores_per_month') == '10' ? 'selected' : '' }}>10 Stores / Month</option>
                                            <option value="1000" {{ old('stores_per_month') == '1000' ? 'selected' : '' }}>Unlimited</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Frequency</label>
                                        <select name="frequency" id="" class="form-control">
                                            <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="quarterly" {{ old('frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                            <option value="yearly" {{ old('frequency') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                            <option value="lifetime" {{ old('frequency') == 'lifetime' ? 'selected' : '' }}>Lifetime</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Trial Period</label>
                                        <select name="trial_period" id="" class="form-control">
                                            <option value="">None</option>
                                            @for($i = 3; $i <= 31; $i++)
                                            <option value="{{ $i }}" {{ (old('trial_period') == $i) ? 'selected' : '' }}>{{ $i }} days</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Trial Price</label>
                                        <input type="number" step="any" name="trial_price" value="0.00" min="0.00" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12">	
                                    <div class="card">
                                        <div class="card-header card-header-primary">
                                            <h4 class="card-title"><strong> Features </strong></h4>
                                            <p class="card-category">Add Memership features descriptions</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">Feature</label>
                                                <input type="text" class="form-control" id="feature" placeholder="Enter Feature">
                                                <button class="btn btn-primary btn-add-feature" type="button">Add</button>
                                            </div>
                                            <hr>
                                            <table class="table table-bordered">
                                                <tbody class="features">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title"><strong> Themes and Color Schemes </strong></h4>
                                    <p class="card-category">Select Themes and Color Schemes for this membership</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <table>
                                            @foreach($themes->take(10) as $theme)
                                            <tr>
                                                <th>{{ $theme->name }}</th>
                                                <th> </th>
                                                <td class="pl-4">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input checkAllThemes" type="checkbox" data-id="{{ $loop->index }}" />Check all
                                                            <span class="form-check-sign">
                                                                <span class="check"></span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <br>
                                                    @foreach($colorSchemes->where('theme_id', $theme->id) as $scheme)
                                                        <div class="form-check form-check-inline checkAllThemes-{{ $loop->parent->index }}">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" value="{{ $scheme->id }}" name="schemes[]" type="checkbox">
                                                                {{ $scheme->name }}
                                                                <span class="form-check-sign">
                                                                    <span class="check"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                    <hr>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title"><strong> Access Rights </strong></h4>
                                    <p class="card-category">Select access rights for this membership</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" id="checkAllAccessRights"/>Check all
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        @foreach($menus as $menu)
                                        <div class="form-check checkAllAccessRights">
                                            <label class="form-check-label">
                                                <input class="form-check-input" value="{{ $menu->id }}" name="access_rights[]" type="checkbox">
                                                {{ $menu->title }}
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title"><strong> Affiliate Stores </strong></h4>
                                    <p class="card-category">Select affiliate stores for this membership</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" id="checkAllAffiliateStores"/>Check all
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        @foreach($features->where('type', 'affiliate_store') as $feature)
                                        <div class="form-check checkAllAffiliateStores">
                                            <label class="form-check-label">
                                                <input class="form-check-input" value="{{ $feature->id }}" name="affiliateStores[]" type="checkbox">
                                                {{ $feature->name }}
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title"><strong> Extra Features </strong></h4>
                                    <p class="card-category">Select extra features for this membership</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" id="checkAllExtraFeatures"/>Check all
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        @foreach($features->where('type', '<>', 'affiliate_store') as $feature)
                                        <div class="form-check checkAllExtraFeatures">
                                            <label class="form-check-label">
                                                <input class="form-check-input" value="{{ $feature->id }}" name="extraFeatures[]" type="checkbox">
                                                {{ $feature->name }}
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('memberships.index') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection

@section('custom-scripts')
    <script>    
        $('.btn-add-feature').on('click', function(){
            var feature = $('#feature').val();
            var html = '';

            if(feature){
                html += '<tr>';
                html += '   <td width="5%">';
                html += '       <a href="#" class="btn btn-sm btn-danger btn-remove-feature">';
                html += '           <i class="material-icons">close</i>';
                html += '       </a>';
                html += '   </td>';
                html += '   <th>'+ feature +'<input type="hidden" name="features[]" value="'+ feature +'"></th>';
                html += '</tr>';
                
                $('.features').append(html)
                $('.btn-remove-feature').on('click', function(){
                    $(this).parent('td').parent('tr').remove();
                });

                $('#feature').val('');
            }
        })

        $(".checkAllThemes").on('click', function () {
            var themeRow = $(this).attr('data-id');
            $(this).parent().contents()[2].textContent = $(this).prop("checked") ? 'Uncheck all' : 'Check all';;
            $(".checkAllThemes-"+ themeRow +" input:checkbox").prop('checked', $(this).prop("checked"));
        });

        $("#checkAllAccessRights").on('click', function () {
            $(this).parent().contents()[2].textContent = $(this).prop("checked") ? 'Uncheck all' : 'Check all';;
            $(".checkAllAccessRights input:checkbox").prop('checked', $(this).prop("checked"));
        });

        $("#checkAllAffiliateStores").on('click', function () {
            $(this).parent().contents()[2].textContent = $(this).prop("checked") ? 'Uncheck all' : 'Check all';;
            $(".checkAllAffiliateStores input:checkbox").prop('checked', $(this).prop("checked"));
        });

        $("#checkAllExtraFeatures").on('click', function () {
            $(this).parent().contents()[2].textContent = $(this).prop("checked") ? 'Uncheck all' : 'Check all';;
            $(".checkAllExtraFeatures input:checkbox").prop('checked', $(this).prop("checked"));
        });
        
    </script>
@endsection

