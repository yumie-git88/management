<?php

namespace App\Http\Controllers;

use App\Models\Product; // 追加
use App\Models\Company; // 追加
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 全ての商品情報を取得
        $products = Product::paginate(10); // all();

        // 商品一覧画面を表示し、取得した全ての商品情報を画面に渡す。
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 商品作成画面で必要な、全ての会社の情報を取得。
        $companies = Company::all();

        //商品作成画面を表示し、取得した全ての会社情報を画面に渡す。
        return view('products.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //リクエストされた情報を確認して、必要な情報が全て揃っているかチェック
        $request->validate([
            'product_name' => 'required', //required必須
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable', //未入力OK
            'img_path' => 'nullable|image|max:2048',
        ]);

        // 新しく商品を作りそのための情報はリクエストから取得
        $product = new Product([
            'product_name' => $request->get('product_name'),
            'company_id' => $request->get('company_id'),
            'price' => $request->get('price'),
            'stock' => $request->get('stock'),
            'comment' => $request->get('comment'),
        ]);

        // リクエストに画像が含まれている場合、その画像を保存
        if($request->hasFile('img_path')){ 
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('products', $filename, 'public');
            $product->img_path = '/storage/' . $filePath;
        }

        // 作成したデータベースに新しいレコードとして保存
        $product->save();

        // 全ての処理が終わったら、商品一覧画面に戻る
        return redirect('products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product) // $id)
    {
        // 商品詳細画面を表示し、商品の詳細情報を画面に渡す
        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product) // $id)
    {
        //
        $companies = Company::all();

        // 商品編集画面を表示し、商品の情報と会社の情報を画面に渡す
        return view('products.edit', compact('product', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product) // $id)
    {
        // リクエストされた情報を確認し、必要な情報が全て揃っているかチェック
        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        // 商品の情報を更新
        $product->product_name = $request->product_name;
        //productモデルのproduct_nameをフォームから送られたproduct_nameの値に書き換える
        $product->price = $request->price;
        $product->stock = $request->stock;

        // 更新した商品を保存
        $product->save();

        // 全ての処理が終わったら、商品一覧画面に戻る。
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product) // $id)
    {
        // 商品を削除
        $product->delete();

        // 全ての処理が終わったら、商品一覧画面に戻る
        return redirect('/products');
    }
}
