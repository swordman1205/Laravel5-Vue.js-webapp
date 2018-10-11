<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Purchase extends Model
{
    protected $guarded = [];

    static $codeDigits = 6;

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'purchases';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseElements()
    {
        return $this->hasMany(PurchaseElement::class);
    }

    /**
     * @return string
     */
    public static function generateNewCode()
    {
        $table = DB::table(static::getTableName());
        $number = $table->max('id') + 1;
        $strlen = strlen($number);

        $random_number = '';

        for($i = 0; $i < (Purchase::$codeDigits - $strlen); $i++) {
            $random_number .= mt_rand(0, 9);
        }

        return 'OR-' . $number . $random_number;
    }

    /**
     * @param $user
     * @return mixed
     */
    public static function createPurchaseForUser($user, $sportMan)
    {
        $purchase = Purchase::create([
            'amount' => 0,
            'user_id' => $user->id,
            'purchase_code' => Purchase::generateNewCode(),
        ]);

        foreach ($user->carts as $cartElement) {
            $purchaseElement = new PurchaseElement([
                'purchase_id' => $purchase->id,
                'user_id' => $user->id,
                'company_id' => $cartElement->buyable->course->company->id,
                'sport_man_first_name' => $sportMan->first_name,
                'sport_man_last_name' => $sportMan->last_name,
                'sport_man_email' => $sportMan->email,
                'sport_man_birthday' => $sportMan->day . '/' . $sportMan->month . '/' . $sportMan->year,
            ]);

            $purchaseElement->save();

            $cartElement->buyable->purchaseElements()->save($purchaseElement);

            $purchase->amount += $cartElement->buyable->price;
        }

        $purchase->save();

        return $purchase;
    }

    /**
     * @param Payment $payment
     */
    public function complete(Payment $payment)
    {
        $this->update([
            'payment_id' => $payment->id,
            'purchased' => true
        ]);
    }
}
