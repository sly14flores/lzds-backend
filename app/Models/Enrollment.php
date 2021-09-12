<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

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
        'payment_method',
        'down_payment',
        'enrollment_school_year',
        'enrollment_date',
        'registered_online',
        'enrollee_rn',
        'enrollment_uiid',
        'old_table_pk',
        'rfid',
        'schedule_id',
        'esc_voucher_grantee',
        'discount_percentage',
        'discount_amount',
        'discount_percentage',
        'total_amount_to_pay',
        'gcash_refno',
        'paypal_refno',
        'payment_confirmed',
        'origin',
        'system_log',
        'update_log',        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'registered_online' => 'boolean',
        'enrollment_date' => 'date',
        'esc_voucher_grantee' => 'boolean',
        'payment_confirmed' => 'boolean'
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
     * @param $value
     * @return false|string
     */
    public function getEnrollmentDateAttribute($value)
    {
        return Carbon::parse($value)->format('F j, Y');
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

    /**
     * Grade/Level
     */
    public function level()
    {
        return $this->belongsTo(GradeLevel::class, 'grade', 'id');
    }

    public function enrollment_fees()
    {
        return $this->hasMany(StudentsFee::class, 'enrollment_id', 'id');
    }

    public function student_discount()
    {
        return $this->hasOne(StudentsDiscount::class, 'enrollment_id', 'id');
    }

    public function student_voucher()
    {
        return $this->hasOne(StudentVoucher::class, 'enrollment_id', 'id');
    }

    public function dtr()
    {
        return $this->hasMany(DtrStudent::class, 'rfid', 'rfid');
    }

}
