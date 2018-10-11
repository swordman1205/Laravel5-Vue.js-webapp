<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['company_id', 'course_id', 'course_day_id', 'user_id', 'confirmed_by_user', 'first_name', 'last_name', 'email', 'birthday', 'datetime'];

    /**
     * @param $reservationInput
     * @param $user
     * @return mixed
     */
    public static function createReservation($reservationInput, $user)
    {
        $course = Course::find($reservationInput['course_id']);

        return self::create([
            'company_id' => $course->company->id,
            'course_id' => $course->id,
            'datetime' => $reservationInput['datetime'],
            'user_id' => $user->id
        ]);
    }

    public function confirm()
    {
        $this->confirmed_by_user = true;
        $this->save();
    }

    public function scopeConfirmed(Builder $query)
    {
        return $query->where('confirmed_by_user', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courseDay()
    {
        return $this->belongsTo('App\CourseDay');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @param $sportsman
     */
    public function saveConfirmation($sportsman)
    {
        $birthday = $sportsman['birthday']['year'] . '-' . ($sportsman['birthday']['month'] + 1) . '-' . $sportsman['birthday']['day'];
        $this->update([
            'first_name' => $sportsman['firstName'],
            'last_name' => $sportsman['lastName'],
            'email' => $sportsman['email'],
            'birthday' => $birthday
        ]);
    }

}
