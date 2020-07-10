<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    // Table Name
    protected $table = 'customers';

    // Primary Key
    public $primaryKey = 'id';

    // Allowing mass assignment
    protected $guarded = [];

    // One to many inverse
    public function country() {
        return $this->belongsTo(Country::class);
    }
}
