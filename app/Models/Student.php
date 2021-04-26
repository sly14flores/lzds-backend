<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lrn',
        'lastname',
        'firstname',
        'middlename',
        'ext_name',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'home_address',
        'contact_no',
        'email_address',
        'student_status',
        'old_school_type',
        'old_school_name',
        'indigenous', 
        'mother_tongue',
        'religion',
        'ethnicity',
        'dialect',
        'siblings_no',
        'gp4ps',
        'gpips',
        'ecd',
        'pwd',
        'pwd_detail',
        'old_table_pk',
        'origin',  
        'system_log',
        'update_log'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'gp4ps' => 'boolean',
        'gpips' => 'boolean',
        'ecd' => 'boolean',
        'pwd' => 'boolean',
    ];

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'system_log';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'update_log';
    
    /**
     * @param $value
     * @return false|string
     */
    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['system_log'])->format('F j, Y h:i A');
    }

    /**
     * @param $value
     * @return false|string
     */
    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['update_log'])->format('F j, Y h:i A');
    }

    public function getDateOfBirthAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Parent(s) / Guardian
     */
    public function parents()
    {
        return $this->hasMany(ParentGuardian::class);
    }

    /**
     * Enrollments
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

}
