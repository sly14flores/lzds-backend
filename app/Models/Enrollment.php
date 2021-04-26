<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'school_id',
        'grade',
        'section',
        'student_status',
        'payment_mode',
        'enrollment_school_year',
        'enrollment_date',
        'registered_online',
        'enrollee_rn',
        'old_table_pk',
        'rfid',
        'schedule_id',
        'origin',
        'system_log',
        'update_log',        
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

    /**
     * Payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * Quetionnaire
     */
    public function questionnaire()
    {
        return $this->hasOne(Questionnaire::class);
    }
    
    /**
     * Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * School Year
     */
    public function school_year()
    {
        return $this->belongsTo(SchoolYear::class, 'enrollment_school_year','id');
    }

}
