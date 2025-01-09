<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ShippingAddress;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\User;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->id());
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $purchases = Purchase::with('product')
            ->where('user_id', auth()->id())
            ->get();
            return view('my-page', compact('user', 'purchases'));
        } elseif ($page === 'sell') {
            $products = Product::where('user_id', auth()->id())->get();
            return view('my-page', compact('user', 'products'));
        }
    }

    public function create()
    {
        $userId = Auth::id();
        $shipping_address = ShippingAddress::where('id', $userId)->first();
        $user = User::where('id',$userId)->first();

        return view('profile', compact('user','shipping_address'));
    }

    public function store(ProfileRequest $profileRequest,
                            AddressRequest $addressRequest, $id) {
        $user = User::find($id);

        if ($profileRequest->hasFile('image')) {
            $imagePath = $profileRequest->file('image')->store('profile_images', 'public');

            $user->profile_image = $imagePath;
            $user->save();
        } else {
            $imagePath = null;
            $user->profile_image = $imagePath;
            $user->save();
        }

        $shipping_address = $addressRequest->only('postal_code', 'address', 'building_name');
        $shipping_address['user_id'] = $id;

        ShippingAddress::create($shipping_address);

        return redirect()->route('home')->with('success', 'プロフィールが作成されました');
    }
}
