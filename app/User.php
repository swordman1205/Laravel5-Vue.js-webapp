<?php

namespace App;

use App\Exceptions\PaymentFailedException;
use App\Jobs\SendResetPasswordEmail;
use DateTime;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'birthday', 'facebook_id', 'google_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $guarded = [];

    /**
     * @param $input
     * @return mixed
     */
    public static function createUser($input)
    {
        $birthday = null;

        if (isset($input['birthday'])) {
            $birthday = new DateTime($input['birthday']);
        }
        return self::create([
            'first_name' => $input['firstName'],
            'last_name' => $input['lastName'],
            'email' => $input['email'],
            'birthday' => $birthday,
            'password' => bcrypt($input['password'])
        ]);
    }

    /**
     * @param $user
     * @param $birthday
     * @return mixed
     */
    public static function createFacebookUser($user, $birthday)
    {
        $password = Hash::make(time() . $user['email']);

        return self::create([
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'birthday' => $birthday,
            'facebook_id' => $user['id'],
            'password' => $password
        ]);
    }

    /**
     * @param $providedUser
     * @param $birthday
     * @return mixed
     */
    public static function createGoogleUser($providedUser, $birthday)
    {
        $user = $providedUser->user;
        $password = Hash::make(time() . $providedUser->email);

        return self::create([
            'first_name' => $user['name']['givenName'],
            'last_name' => $user['name']['familyName'],
            'email' => $providedUser->email,
            'birthday' => $birthday,
            'google_id' => $providedUser->id,
            'password' => $password
        ]);
    }

    /**
     * @param $email
     * @param $facebookId
     * @return mixed
     */
    public static function emailOrFacebookIdExists($email, $facebookId)
    {
        return self::where('email', $email)->orWhere('facebook_id', $facebookId)->first();
    }

    /**
     * @param $email
     * @param $googleId
     * @return mixed
     */
    public static function emailOrGoogleIdExists($email, $googleId)
    {
        return self::where('email', $email)->orWhere('google_id', $googleId)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseElements()
    {
        return $this->hasMany(PurchaseElement::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\UserRole', 'user_role_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany('App\Reservation')->with(['course', 'courseDay']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function friends()
    {
        return $this->hasMany('App\Friend');
    }

    /**
     * @param $password
     */
    public function updatePassword($password)
    {
        $this->password = Hash::make($password);
        $this->save();
    }

    /**
     * @param $email
     */
    public function updateEmail($email)
    {
        $this->email = $email;
        $this->save();
    }

    /**
     * @param $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;
        $this->save();
    }

    /**
     * @param $googleId
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;
        $this->save();
    }

    /**
     * @return bool
     */
    public function hasCompany()
    {
        return $this->companies()->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class)->withTimestamps();
    }

    /**
     * Alias of isCompanyMember
     * @param Company $company
     * @return bool
     */
    public function hasThisCompany(Company $company)
    {
        return $this->isCompanyMember($company->id);
    }

    /**
     * @param $companyId
     * @return bool
     */
    public function isCompanyMember($companyId)
    {
        return $this->belongsToMany('App\Company')->where('company_user.company_id', $companyId)->exists();
    }

    /**
     * @return bool
     */
    public function isCompanyOwner()
    {
        return $this->belongsToMany('App\Company')->where('companies.registrant_id', $this->id)->exists();
    }

    /**
     * @param $token
     */
    public function saveCardInfos($token)
    {
        $this->createAsStripeCustomerIfNotExists($token);
        $this->updateCard($token);
    }

    /**
     * @param $token
     */
    public function createAsStripeCustomerIfNotExists($token)
    {
        if (!$this->hasStripeId()) {
            $this->createAsStripeCustomer($token);
        }
    }

    /**
     * @param Purchase $purchase
     * @throws PaymentFailedException
     */
    public function chargeWithStripe(Purchase $purchase)
    {
        if ($this->charge(floor($purchase->amount * 100), ['currency' => 'EUR'])) {
            $payment = Payment::create([
                'name' => '',
                'status' => 1,
                'user_id' => $this->id,
                'quantity' => 1,
                'amount' => $purchase->amount * 100,
                'purchase_id' => $purchase->id
            ]);

            $purchase->complete($payment);
            $this->emptyCart();
        } else {
            throw new PaymentFailedException();
        }
    }

    /**
     * Svuota il carrello
     */
    public function emptyCart()
    {
        $this->carts()->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        SendResetPasswordEmail::dispatch($this, $token);
    }
}
