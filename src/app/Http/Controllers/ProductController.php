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
use App\Models\Color;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $page = $request->query('tab', null);
        $keyword = $request->input('keyword');

        $productQuery = Product::query()->where('user_id', '!=', $userId);

        if ($page === 'mylist') {
            $productQuery->whereHas('likedByUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
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
        $colors = Color::all();

        return view('products.create', compact('categories','conditions','colors'));
    }

    public function store(ExhibitionRequest $request)
    {
        DB::beginTransaction();

        try {
            $productData = $request->only('color_id', 'condition', 'name', 'brand_name', 'price', 'description');
            $productData['user_id'] = Auth::id();
            $productData['status'] = '販売中';

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product_images', 'public');
                $productData['image'] = $imagePath;
            }
            $product = Product::create($productData);
            $product->categories()->attach($request->input('category_id'));

            DB::commit();

            return redirect()->route('my-page')->with(['success' => '商品が出品されました。']);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => '商品の登録に失敗しました。']);
        }
    }

    public function showDetail($id)
    {
        $product = Product::with(['categories','comments','likedByUsers','color'])->find($id);

        $authId = auth()->id();
        $isLiked = $authId ? $product->likedByUsers->contains($authId) : false;
        $isOwner = $product->user_id === $authId;

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

        DB::beginTransaction();

        try {
            if ($likes) {
                $likes->delete();
            } else {
                Like::create([
                    'user_id' => $userId,
                    'product_id' => $id,
                ]);
            }
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'エラーが発生しました');
        }
    }

    public function reply(Request $request, $id)
    {
        $comment = Comment::find($id);

        $fieldName = 'reply' . $comment->user_id;
        $request->validate([
            $fieldName => 'required|max:255|string',
        ],[
            $fieldName . '.required' => '返信内容を入力してください。',
            $fieldName . '.string'=> '返信は文字列で入力してください。',
            $fieldName . '.max' => '返信は255文字以内で入力してください。'
        ]);

        if ($comment) {
            $comment->update(['reply' => $request->input($fieldName)]);
            return redirect()->back()->with('success', '返信を送信しました。');
        } else {
            return redirect()->back()->with('error', 'コメントが見つかりません。');
        }
    }
}
