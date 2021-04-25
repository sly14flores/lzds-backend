<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentGuardian extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parents_guardians';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'relationship',
        'full_name',
        'guardian_relationship',
        'last_name',
        'first_name',
        'middle_name',
        'ext_name',
        'maiden_name',
        'occupation',
        'monthly_income',
        'contact_no',
        'old_table_pk',        
        'system_log',
        'update_log'        
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

}
