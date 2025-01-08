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
        $page = $request->query('page','recommend');

        if ($page === 'recommend') {
            $products = Product::all();
        } elseif ($page === 'my-list') {
            $products = Product::whereHas( 'likedByUsers', function ($query) use ($userId) {
                $query->where('user_id',$userId);
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

    public function show($id)
    {
        $product = Product::with(['categories','comments'])->find($id);
        $comment = Comment::where('product_id', $id)->count();
        $likes = Like::where('product_id', $id)->count();

        return view('products.detail', compact('product', 'comment','likes'));
    }

    public function edit($id)
    {
        $userId = Auth::id();
        $shipping_address = ShippingAddress::where('user_id', $userId)->first();

        return view('address', compact('shipping_address'));
    }

    public function update(AddressRequest $request, $id)
    {
        $addressData = $request->only('postal_code', 'address', 'building_name');
        $shipping_address = ShippingAddress::find($id);
        $shipping_address->update($addressData);

        return redirect()->route('')->with('住所が更新されました。');
    }

    public function storeComment(CommentRequest $request)
    {
        Comment::create([
            'content' => $request->input('comment'),
            'user_id' => Auth::id(),
            'product_id' => $request->input('product_id'),
        ]);
        return redirect()->back()->with('コメントを送信しました。');
    }


}
