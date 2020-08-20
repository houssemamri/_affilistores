<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'jvzoo_product_id', 'title', 'upgrade_membership_url', 'product_price', 'frequency', 'trial_period', 'trial_price', 'stores_per_month', 'next_upgrade_membership_id'
    ];

    public function features(){
        return $this->hasMany('App\MembershipFeature');
    }

    public function members(){
        return $this->hasMany('App\MemberDetail');
    }

    public function accessRights(){
        return $this->hasMany('App\AccessRight');
    }

    public function accessThemes(){
        return $this->hasMany('App\AccessTheme');
    }

    public function accessColorSchemes(){
        return $this->hasMany('App\AccessColorScheme');
    }

    public function accessFeatures(){
        return $this->hasMany('App\AccessFeature');
    }

}
