<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\facades\Auth;
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
        return view('products.create');
    }

    public function store(Request $request)
    {
        return redirect()->back();
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
        return view('products.address');
    }

    public function update(Request $request, $id)
    {
        return redirect()->back();
    }

    public function storeComment(Request $request)
    {
        $userId = Auth::id();
        Comment::create([
            'content' => $request->input('comment'),
            'user_id' => $userId,
            'product_id' => $request->input('product_id'),
        ]);
        return redirect()->back()->with('コメントを送信しました。');
    }

    
}
