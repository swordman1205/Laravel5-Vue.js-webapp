<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseSubscription extends Model
{
    protected $guarded = [];

    protected $table = 'course_subscription';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
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
    public function purchaseElements()
    {
        return $this->morphMany(PurchaseElement::class, 'buyable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'buyable');
    }
}
