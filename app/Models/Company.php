<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    //「カテゴリ(company)はたくさんの商品(products)をもつ」というリレーション関係を定義する
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
