@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品情報一覧</h1>

    <!-- 検索フォームのセクション -->
    <div class="search mt-5">
        
        <!-- 検索のタイトル -->
        <h2>検索フォーム</h2>
        
        <!-- 検索フォーム。GETメソッドで、商品一覧のルートにデータを送信 -->
        <form action="{{ route('products.index') }}" method="GET" class="row g-3">

            <!-- 商品名検索用の入力欄 -->
            <div class="col-sm-12 col-md-3">
                <input type="text" name="search" class="form-control" placeholder="商品名" value="{{ request('search') }}">
            </div>

            <!--メーカー名検索用の入力欄 -->
            <div class="col-sm-12 col-md-2">
                <select name="company_id" id="company_id" class="form-control" data-toggle="select" value="{{ old('company_id')}}">
                    <option value="">未選択</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 検索ボタン -->
            <div class="col-sm-12 col-md-1">
                <button class="btn btn-success" type="submit">検索</button> <!-- btn色交換 -->
            </div>
            
            <!-- 検索条件をリセットするリンクボタン -->
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary col-md-2">検索条件をリセット</a>
        </form>
    </div>

    <div>
    <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">商品新規登録</a>
    </div>

    <div class="products mt-5">
        <h2>商品情報</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>メーカー名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>コメント</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->comment }}</td>
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細表示</a>
                        <!-- <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-sm mx-1">編集</a> -->
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('本当に削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- {{ $products->links() }} -->
    {{ $products->appends(request()->query())->links() }}

</div>
@endsection
