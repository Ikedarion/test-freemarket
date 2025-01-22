<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Requests\PurchaseRequest;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\ShippingAddress;
use Stripe\Stripe;
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function showPurchaseForm($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect('/')->with('error', '商品が見つかりませんでした。');
        }

        $shipping_address = ShippingAddress::where('user_id', auth()->id())->first();

        return view('purchase', compact('product', 'shipping_address'));
    }

    public function createCheckoutSession(PurchaseRequest $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $productId = $request->input('product_id');
            $shippingAddressId = $request->input('shipping_address_id');
            $paymentMethod = $request->input('payment_method');

            $paymentMethodTypes = ['card'];
            if ($paymentMethod === 'コンビニ') {
                $paymentMethodTypes = ['konbini'];
            }

            $product = Product::find($productId);

            if (!$product) {
                return redirect()->route('home')->with('error', '商品が見つかりませんでした。');;
            }

            $purchase = Purchase::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'shipping_address_id' => $shippingAddressId,
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'stripe_payment_id' => '',
                'price' => $product->price,
            ]);

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => $paymentMethodTypes,
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => "商品ID: {$productId}",
                        ],
                        'unit_amount' => (int)$product->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'customer_email' => auth()->user()->email,
                'success_url' => route('payment.success', ['purchaseId' => $purchase->id]),
                'cancel_url' => route('payment.cancel', ['purchaseId' => $purchase->id]),
            ]);

            $product->status = '取引中';
            $product->save();
            $purchase->stripe_payment_id = $session->id;
            $purchase->save();

            return response()->json([
                'id' => $session->id,
                'payment_method' => $paymentMethod
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe Checkout セッション作成エラー: ' . $e->getMessage());
            return response()->json(['予期しないエラーが発生しました。後ほど再試行してください。'], 500);
        }
    }

    public function success($purchaseId)
    {
        $purchase = Purchase::where('id', $purchaseId)->first();
        $purchase->payment_status = 'succeeded';
        $purchase->save();

        $completedAt = $purchase->updated_at;

        $product = Product::find($purchase->product_id);
        if ($product) {
            $product->status = '売却済み';
            $product->save();
        }

        Mail::to($purchase->user->email)
        ->send(new PaymentSuccessMail($purchase, $completedAt));
        return view('success', compact('product'));
    }

    public function cancel($purchaseId)
    {
        $purchase = Purchase::where('id', $purchaseId)->first();
        if ($purchase) {
            $purchase->payment_status = 'failed';
            $purchase->save();

            $product = Product::find($purchase->product_id);
            if ($product) {
                $product->status = '販売中';
                $product->save();
            }
            return redirect()->route('product.show', ['id' => $product->id])->with('error', '決済がキャンセルされました。');
        }
        return redirect()->route('home')->with('error', '購入情報が見つかりませんでした。');
    }
}
