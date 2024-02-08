<?php

namespace App\Http\Controllers;

use App\Models\Product; // 追加変更
use App\Models\Company; // 追加 Companies;変更なし
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // 追加
use App\Http\Controllers\Controller; // 追加

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) //)
    {
        // $company = new Company;

        //$companies = Companies::query(); // テーブルから全てのレコードを取得する 追加
        
        // モデルに基づいてクエリビルダを初期化
        $query = Product::query(); // この行の後にクエリを逐次構築

        // 検索フォームに入力された値を取得
        $search = $request->input('search');
        $company_id = $request->input('company_id');

        // 商品名の検索キーワードがある場合、そのキーワードを含む商品をクエリに追加
        if($search = $request->search){
            $query->where('product_name', 'LIKE', "%{$search}%");
        }

        // メーカー名が同じ場合、そのメーカー名をクエリに追加
        if($company_id = $request->company_id){
            $query->where('company_id', '=', $company_id); // company_name
        }

        // ソートのパラメータが指定されている場合、そのカラムでソートを行う
        if($sort = $request->sort){
            $direction = $request->direction == 'desc' ? 'desc' : 'asc'; // directionがdescでない場合は、デフォルトでascとする
            $query->orderBy($sort, $direction);
        }

        $reviews = $query->get();

        $companies = Company::all(); //追加 テーブルから全てのレコードを取得する

        // 上記の条件に基づいて商品を取得し、10件ごとのページネーションを適用
        $products = $query->paginate(10); // $products = Product::paginate(10); // all();

        // 商品一覧画面を表示し、取得した全ての商品情報を画面に渡す
        // return view('products.index', ['products' => $products, 'companies' => $companies]);
        return view('products.index', compact('products', 'companies'));
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
        $rules = ['parameter' => 'alpha:ascii'];

        // アルファベット
        $data = ['parameter' => 'a'];
        Validator::make($data, $rules)->passes(); // true

        // 日本語
        $data = ['parameter' => 'あいうえお'];
        Validator::make($data, $rules)->passes(); // false

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
        return redirect('products',);
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

    //「\\」「%」「_」などの記号を文字としてエスケープさせる
    public static function escapeLike($str)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $str);
    }
}
