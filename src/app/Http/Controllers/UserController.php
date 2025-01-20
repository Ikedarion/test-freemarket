<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\ShippingAddress;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\User;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->id());
        $keyword = $request->input('keyword');
        $page = $request->query('tab', 'sell');

        $products = collect();
        $purchases = collect();

        if ($page === 'buy') {
            $purchasesQuery = Purchase::with('product')
            ->where('user_id', auth()->id())
            ->where(function ($query) {
                $query->where('payment_status', 'pending')
                ->orWhere('payment_status', 'succeeded');
            });

            if ($keyword) {
                $purchasesQuery->keywordSearch($keyword);
            }
            $purchases = $purchasesQuery->get();
        } else {
            $productsQuery = Product::where('user_id', auth()->id());

            if ($keyword) {
                $productsQuery->keywordSearch($keyword);
            }
            $products = $productsQuery->get();
        }

        return view('my-page', compact('user', 'products', 'purchases'));
    }

    public function create()
    {
        $userId = Auth::id();
        $user = User::where('id',$userId)->first();
        $shipping_address = ShippingAddress::where('user_id', $userId)->first();

        return view('profile', compact('user','shipping_address'));
    }

    public function store(ProfileRequest $profileRequest,
                            AddressRequest $addressRequest, $id) {
        $user = User::findOrFail($id);

        $user->name = $profileRequest->input('name');

        if ($profileRequest->hasFile('image')) {
            $imagePath = $profileRequest->file('image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        } else {
            $imagePath = null;
            $user->profile_image = $imagePath;
        }

        $user->save();

        $shipping_address = $addressRequest->only('postal_code', 'address', 'building_name');
        $shipping_address['user_id'] = $id;
        ShippingAddress::create($shipping_address);

        return redirect()->route('home')->with('success', 'プロフィールが作成されました');
    }

    public function update(ProfileRequest $profileRequest,
                            AddressRequest $addressRequest, $id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $userData = [
                'name' => $profileRequest->input('name'),
            ];

            if ($profileRequest->hasFile('image')) {
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                $imagePath = $profileRequest->file('image')->store('profile_images', 'public');
                $userData['profile_image'] = $imagePath;
            }

            $user->update($userData);

            $shipping_address = ShippingAddress::where('user_id', $id)->first();
            $shipping_addressData = $addressRequest->only('postal_code', 'address', 'building_name');

            $shipping_address->update($shipping_addressData);

            DB::commit();

            return redirect()->route('home')->with('success', 'プロフィールが更新されました');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'プロフィールの更新に失敗しました。');
        }
    }

    public function edit($id)
    {
        $product_id = Product::find($id);
        $shipping_address = ShippingAddress::where('user_id', auth()->id())->first();
        if (!$shipping_address) {
            return redirect()->back()->with('error', '配送先情報が見つかりません。');
        }

        return view('address', compact('shipping_address', 'product_id'));
    }

    public function updateAddress(AddressRequest $request, $id)
    {
        $addressData = $request->only('postal_code', 'address', 'building_name');
        $shipping_address = ShippingAddress::find($id);

        $shipping_address->update($addressData);

        return redirect()->route('purchase', ['id' => $request->input('product_id')])->with('success', '住所の変更が完了しました。');
    }
}
