<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\facades\Auth;
use Illuminate\support\facades\DB;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
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
        $keyword = $request->input('keyword');

        $productQuery = Product::query()->where('user_id', '!=', $userId);

        if ($page === 'my-list') {
            $productQuery->whereHas('likedByUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();
        }

        if ($keyword) {
            $productQuery = $productQuery->keywordSearch($keyword);
        }

        $products = $productQuery->get();

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
        DB::beginTransaction();

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

            DB::commit();

            return redirect()->route('my-page')->with(['success' => '商品の出品が完了しました。']);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => '商品の登録に失敗しました。']);
        }
    }

    public function showDetail($id)
    {
        $product = Product::with(['categories','comments','likedByUsers'])->find($id);

        $isLiked = $product->likedByUsers->contains(auth()->id());
        $isOwner = $product->user_id === auth()->id();

        return view('products.detail', compact('product','isLiked', 'isOwner'));
    }

    public function storeComment(CommentRequest $request)
    {
        Comment::create([
            'content' => $request->input('content'),
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
