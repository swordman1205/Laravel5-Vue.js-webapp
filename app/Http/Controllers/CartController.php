<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CourseSubscription;
use App\LessonPackage;
use App\Purchase;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MetaTag;

class CartController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $cartElements = $user->carts;
        $totalPrice = 0;

        foreach ($cartElements as $cartElement) {
            $cartElement->buyable();
            $cartElement->buyable->course;
            $totalPrice += $cartElement->buyable->price;

            if($cartElement->buyable instanceof CourseSubscription)
            {
                $cartElement->buyable->subscription;
                $totalPrice += $cartElement->buyable->subscription->price;
            }
        }
        MetaTag::set('title', 'Carello');

        return view('cart.index', [
            'cartElements' => $cartElements,
            'friends' => $user->friends,
            'totalPrice' => $totalPrice
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function summaryIndex(Purchase $purchase)
    {
        $purchaseElements = $purchase->purchaseElements;

        foreach ($purchaseElements as $element) {
            $element->buyable->course;
        }

        MetaTag::set('title', 'Carello');

        return view('cart.partials.summary', [
            'user' => Auth::user(),
            'purchase' => $purchase,
            'purchaseElements' => $purchaseElements,
        ]);
    }

    /**
     * @param $id
     */
    public function deleteCartElement($id)
    {
        $user = Auth::user();

        foreach ($user->carts as $cartElement) {
            if ($cartElement->id == $id) {
                $cartElement->delete();
            }
        }
    }

    public function addToCart(Request $request)
    {
        if ($request->type == CourseSubscription::class) {
            $courseSubscription = CourseSubscription::find($request->buyable['id']);

            Cart::create([
                'user_id' => Auth::user()->id,
                'buyable_id' => $courseSubscription->id,
                'buyable_type' => CourseSubscription::class,
            ]);
        } else if ($request->type == LessonPackage::class) {
            $lessonPackage = LessonPackage::find($request->buyable['id']);

            Cart::create([
                'user_id' => Auth::user()->id,
                'buyable_id' => $lessonPackage->id,
                'buyable_type' => LessonPackage::class,
            ]);
        }
    }
}
