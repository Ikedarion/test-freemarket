<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\AddressRequest;
use App\Models\ShippingAddress;
use App\Models\Category;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Like;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $page = $request->query('page', null);

        if ($page === null) {
            $products = Product::all();
        } elseif ($page === 'my-list') {
            $products = Product::whereHas('likedByUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();
        }

        return view('index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = [
            '良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い'
        ];
        $colors = [
            'レッド','ピンク','オレンジ','イエロー','グリーン','ブルー','ネイビー','パープル','ブラウン','ブラック','ホワイト','グレー','ベージュ','ゴールド','シルバー','クリア','マルチカラー'
        ];

        return view('products.create', compact('categories','conditions','colors'));
    }

    public function store(ExhibitionRequest $request)
    {
        try {
            $productData = $request->only('color', 'condition', 'name', 'brand_name', 'price', 'description');
            $productData['user_id'] = Auth::id();
            $productData['status'] = '販売中';

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product_images', 'public');
                $productData['image'] = $imagePath;
            }
            $product = Product::create($productData);

            $product->categories()->attach($request->input('category_id'));
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => '商品の登録に失敗しました。']);
        }

        return redirect()->route('my-page')->with(['success' => '商品の出品が完了しました。']);
    }

    public function showDetail($id)
    {
        $product = Product::with(['categories','comments','likedByUsers'])->find($id);

        $isLiked = $product->likedByUsers->contains(auth()->id());

        return view('products.detail', compact('product','isLiked'));
    }

    public function showPurchaseForm($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect('/')->with('error', '商品が見つかりません。');
        }

        $shipping_address = ShippingAddress::where('user_id', auth()->id())->first();

        return view('purchase',compact('product','shipping_address'));
    }

    public function edit($id)
    {
        $product_id = Product::find($id)->id;
        $shipping_address = ShippingAddress::where('user_id', auth()->id())->first();

        return view('address', compact('shipping_address', 'product_id'));
    }

    public function update(AddressRequest $request, $id)
    {
        $addressData = $request->only('postal_code', 'address', 'building_name');
        $shipping_address = ShippingAddress::find($id);

        $shipping_address->update($addressData);

        return redirect()->route('purchase', ['id' => $request->input('product_id')]);
    }

    public function storeComment(CommentRequest $request)
    {
        Comment::create([
            'content' => $request->input('comment'),
            'user_id' => Auth::id(),
            'product_id' => $request->input('product_id'),
        ]);

        return redirect()->back()->with('success','コメントを送信しました。');
    }

    public function like($id)
    {
        $userId = Auth::id();
        $likes = like::where('user_id', $userId)
                    ->where('product_id', $id)->first();

        if($likes) {
            $likes->delete();
            return redirect()->back();
        } else {
            Like::create([
                'user_id' => $userId,
                'product_id' => $id,
            ]);
            return redirect()->back();
        }
    }


}
