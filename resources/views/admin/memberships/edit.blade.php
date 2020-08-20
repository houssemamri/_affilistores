@extends('admin.master')

@section('page_title')
Edit Membership
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('memberships.edit', $id) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong>Edit Membership </strong></h4>
                            <p class="card-category">Update your membership</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Title</label>
                                        <input type="text" name="title" value="{{ $membership->title }}" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">JVZoo Product Id</label>
                                    <input type="text" name="jvzoo_product_id" value="{{ $membership->jvzoo_product_id }}" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Upgrade Membership URL</label>
                                    <input type="text" name="upgrade_membership_url" value="{{ $membership->upgrade_membership_url }}" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Next Upgrade Membership</label>
                                        <select name="next_upgrade_membership_id" class="form-control">
                                            @foreach($memberships as $otherMembership)
                                            <option value="{{ $otherMembership->id }}" {{ $membership->next_upgrade_membership_id == $otherMembership->id ? 'selected' : '' }}>{{ $otherMembership->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Product Price</label>
                                        <input type="number" step="any" name="product_price" value="{{ $membership->product_price }}" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">No. of Stores Per Month</label>
                                        <select name="stores_per_month" id="" class="form-control">
                                            <option value="5" {{ $membership->stores_per_month == '5' ? 'selected' : '' }}>5 Stores / Month</option>
                                            <option value="10" {{ $membership->stores_per_month == '10' ? 'selected' : '' }}>10 Stores / Month</option>
                                            <option value="1000" {{ $membership->stores_per_month == '1000' ? 'selected' : '' }}>Unlimited</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Frequency</label>
                                        <select name="frequency" id="" class="form-control">
                                            <option value="monthly" {{ ($membership->frequency == '1' ? 'selected' : '') }}>Monthly</option>
                                            <option value="quarterly" {{ ($membership->frequency == '3' ? 'selected' : '') }}>Quarterly</option>
                                            <option value="yearly" {{ ($membership->frequency == '12' ? 'selected' : '') }}>Yearly</option>
                                            <option value="lifetime" {{ ($membership->frequency == '120' ? 'selected' : '' ) }}>Lifetime</option>
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
                                            <option value="{{ $i }}" {{ ($membership->trial_period == $i) ? 'selected' : '' }}>{{ $i }} days</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Trial Price</label>
                                        <input type="number" step="any" name="trial_price" value="{{ $membership->trial_price }}" min="0.00" class="form-control" >
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
                                                    @foreach($membership->features as $featureDescription)
                                                        <tr>
                                                            <td width="5%">
                                                                <a href="#" class="btn btn-sm btn-danger btn-remove-feature">
                                                                    <i class="material-icons">close</i>
                                                                </a>
                                                            </td>
                                                            <th>
                                                                {{ $featureDescription->feature }}
                                                                <input type="hidden" name="features[]" value="{{ $featureDescription->feature }}">
                                                            </th>
                                                        </tr>
                                                    @endforeach
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
                                                            <input class="form-check-input checkAllThemes" type="checkbox" data-id="{{ $loop->index }}"  />Check All
                                                            <span class="form-check-sign">
                                                                <span class="check"></span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <br>
                                                    @foreach($colorSchemes->where('theme_id', $theme->id) as $scheme)
                                                        <div class="form-check form-check-inline checkAllThemes-{{ $loop->parent->index }}">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" value="{{ $scheme->id }}" name="schemes[]" type="checkbox" {{ $membership->accessColorSchemes->where('color_scheme_id', $scheme->id)->count() > 0 ? 'checked' : '' }}>
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
                                                <input class="form-check-input" value="{{ $menu->id }}" name="access_rights[]" type="checkbox" {{ ($membership->accessRights->where('member_menu_id', $menu->id)->count() > 0) ? 'checked' : '' }}>
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
                                                <input class="form-check-input" value="{{ $feature->id }}" name="affiliateStores[]" type="checkbox" {{ ($membership->accessFeatures->where('feature_id', $feature->id)->count() > 0) ? 'checked' : '' }}>
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
                                                <input class="form-check-input" value="{{ $feature->id }}" name="extraFeatures[]" type="checkbox" {{ ($membership->accessFeatures->where('feature_id', $feature->id)->count() > 0) ? 'checked' : '' }}>
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

        $('.btn-remove-feature').on('click', function(){
            $(this).parent('td').parent('tr').remove();
        });

        $("input.checkAllThemes").on('click', function () {
            var themeRow = $(this).attr('data-id');
            $(this).parent().contents()[2].textContent = $(this).prop("checked") ? 'Uncheck all' : 'Check all';;
            $(".checkAllThemes-"+ themeRow +" input:checkbox").prop('checked', $(this).prop("checked"));
        });

        $("input#checkAllAccessRights").on('click', function () {
            $(this).parent().contents()[2].textContent = $(this).prop("checked") ? 'Uncheck all' : 'Check all';;
            $(".checkAllAccessRights input:checkbox").prop('checked', $(this).prop("checked"));
        });

        $("input#checkAllAffiliateStores").on('click', function () {
            $(this).parent().contents()[2].textContent = $(this).prop("checked") ? 'Uncheck all' : 'Check all';;
            $(".checkAllAffiliateStores input:checkbox").prop('checked', $(this).prop("checked"));
        });

        $("input#checkAllExtraFeatures").on('click', function () {
            $(this).parent().contents()[2].textContent = $(this).prop("checked") ? 'Uncheck all' : 'Check all';;
            $(".checkAllExtraFeatures input:checkbox").prop('checked', $(this).prop("checked"));
        });
    </script>
@endsection
