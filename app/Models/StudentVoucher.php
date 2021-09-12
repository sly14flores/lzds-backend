<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentVoucher extends Model
{
    use HasFactory;

    protected $table = 'students_vouchers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enrollment_id',
        'amount',
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
