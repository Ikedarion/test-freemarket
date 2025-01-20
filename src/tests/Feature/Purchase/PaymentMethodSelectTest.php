<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session as StripeSession;
use Tests\TestCase;
use App\Models\ShippingAddress;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Stripe\StripeClient;
use Mockery;
class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 支払い方法が正しく反映されるかのテスト.
     *
     * @return void
     */
    public function testPaymentMethodSelection()
    {
    }
}
