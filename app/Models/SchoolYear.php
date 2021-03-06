<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_year',
        'is_current',
        'system_log',   
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function isCurrent()
    {
        return $this->is_current == true;
    }

    /**
     * Enrollments
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class,'enrollment_school_year','id');
    }

     /**
      * Fees
      */
    public function fees()
    {
        return $this->hasMany(Enrollment::class,'school_year','id');
    }

}
