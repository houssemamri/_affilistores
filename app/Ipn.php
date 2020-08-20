<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ipn extends Model
{
    protected $fillable = [
        'sys', 'ctransreceipt', 'ccustemail', 'ccustname', 'ctransvendor', 'cproditem', 'cprodtype', 'ctransaction', 'ctransamount', 'ctranstime', 
    ];
}
