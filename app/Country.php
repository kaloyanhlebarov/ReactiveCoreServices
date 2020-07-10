<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // Table Name
    protected $table = 'countries';

    // Primary Key
    public $primaryKey = 'id';

    // Allowing mass assignment
    protected $guarded = [];

    // Timestamps
    public $timestamps = false;

    // One to many
    public function customers() {
        return $this->hasMany(Customer::class);
    }

    public function getFullInformationAttribute() {
        return $this->capital ? true : false;
    }
}
