<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class PaymentController extends Controller
{
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

            $product = Product::findOrFail($productId);

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => $paymentMethodTypes,
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => "商品ID: {$productId}",
                        ],
                        'unit_amount' => $product->price * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'customer_email' => auth()->user()->email,
                'success_url' => route('payment.success', ['id' => $product->id]),
                'cancel_url' => route('payment.cancel', ['id' => $product->id]),
            ]);

            Purchase::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'shipping_address_id' => $shippingAddressId,
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'stripe_payment_id' => $session->id,
                'price' => $product->price,
            ]);

            $product->status = '取引中';
            $product->save();

            return response()->json(['id' => $session->id]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe Checkout セッション作成エラー: ' . $e->getMessage());
            return response()->json(['予期しないエラーが発生しました。後ほど再試行してください。'], 500);
        }
    }

    public function success($id)
    {
        $product = Product::find($id);
        $product->status = '売却済み';
        $product->save();

        $purchase = Purchase::where('product_id', $id)
                ->where('user_id', auth()->id())->first();
        if ($purchase) {
            $purchase->payment_status = 'succeeded';
            $purchase->save();
        }
        return view('success', compact('purchase'));
    }

    public function cancel($id)
    {
        $product = Product::find($id);

        $product->status = '販売中';
        $product->save();

        return view('cancel');
    }
}
