<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtrStudent extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dtr_students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rfid',
        'ddate',
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out',
        'tardiness',
        'undertime',
        'absent',
        'is_halfday',
        'system_log',
    ];
  
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'system_log';
    
    /**
     * @param $value
     * @return false|string
     */
    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['system_log'])->format('F j, Y h:i A');
    }

    public function enrollment()
    {
        return $this->belongsTo(Student::class, 'rfid', 'rfid');
    }

}
