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

            <!--メーカー名検索用の選択欄 -->
            <div class="col-sm-12 col-md-2">
                <select name="company_id" id="company_id" class="form-control" data-toggle="select" 
                    value="{{ old('company_id')}}">
                    <option value="">未選択</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 検索ボタン -->
            <div class="col-sm-12 col-md-1">
                <button class="btn btn-success search-form" type="submit" name="search-form">検索</button>
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
        <table class="table table-striped" id="table-sort">
            <thead>
                <tr>
                    <th data-toggle="tooltip" title="↑↓並び替え" class="table-th">ID</th>
                    <th class="table-th">商品画像</th>
                    <th data-toggle="tooltip" title="↑↓並び替え" class="table-th">商品名</th>
                    <th data-toggle="tooltip" title="↑↓並び替え" class="table-th">メーカー名</th>
                    <th data-toggle="tooltip" title="↑↓並び替え" class="table-th">価格</th>
                    <th data-toggle="tooltip" title="↑↓並び替え" class="table-th">在庫数</th>
                    <th class="table-th">コメント</th>
                    <th class="table-th">操作</th>
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
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1 mb-1">詳細表示</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline"
                            onsubmit="return confirm('本当に削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mx-1 mb-1">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{ $products->appends(request()->query())->links() }}

    <script> //ajax
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $("[name='csrf-token']").attr("content") },
        })
        $('.search-form').on('click', function(){
            let txtSearch = $('input[name="search"]').val();
            let drpSearch = $('input[name="company_id"]').val();
            $.ajax({
                url: '/product/index/',
                method: "GET", //"POST",
                // data: {
                //     txtSearch : txtSearch,
                //     drpSearch : drpSearch,
                // },
                dataType: "json",
            }).done(function(data){ // 通信成功
                console.log(data);
            }).fail(function(jqXHR, textStatus, errorThrown){ // 通信の失敗
                alert('検索が失敗しました');
                console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラー
                console.log("errorThrown    : " + errorThrown.message); // 例外情報
                console.log("URL            : " + url);
            });
        });
    </script>
</div>
@endsection
