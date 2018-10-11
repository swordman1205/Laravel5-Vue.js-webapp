<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonPackage extends Model
{
    protected $fillable = ['course_id', 'n_lessons', 'price', 'start_date', 'end_date'];

    /**
     * @param $courseId
     */
    public function updateCourseId($courseId){
        $this->course_id = $courseId;
        $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(SubscriptionService::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function carts()
    {
        return $this->morphMany(Cart::class, 'buyable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'buyable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function purchaseElements()
    {
        return $this->morphMany(PurchaseElement::class, 'buyable');
    }
}