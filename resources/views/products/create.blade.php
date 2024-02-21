@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品新規登録</h1>

    
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        
        @csrf


        <div class="mb-3">
            <label for="product_name" class="form-label">商品名<span class="text-danger">*</span></label>
            <input id="product_name" type="text" name="product_name" class="form-control" required
                value="{{ old('product_name')}}">
        </div>
        
        <div class="mb-3">
            <label for="company_id" class="form-label">メーカー名<span class="text-danger">*</span></label>
            <select class="form-select" id="company_id" name="company_id">
                @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="price" class="form-label">価格<span class="text-danger">*</span></label>
            <input id="price" type="text" name="price" class="form-control" required value="{{ old('price')}}">
        </div>
        
        <div class="mb-3">
            <label for="stock" class="form-label">在庫数<span class="text-danger">*</span></label>
            <input id="stock" type="text" name="stock" class="form-control" required value="{{ old('stock')}}">
        </div>
        
        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea id="comment" name="comment" class="form-control" rows="3">{{ old('comment')}}</textarea>
        </div>
        
        <div class="mb-3">
            <label for="img_path" class="form-label">商品画像</label>
            <input id="img_path" type="file" name="img_path" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary me-2">登録</button>

        <a href="{{ route('products.index') }}" class="btn btn-primary">商品一覧に戻る</a>
    </form>

</div>
@endsection
