<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $primaryKey = 'book_id';

    protected $fillable = [
        'title',         // 書名
        'author',        // 作者
        'publisher',     // 出版社
        'publish_date',  // 出版日期
        'category',      // 類別
        'description',   // 描述
        'isbn',          // ISBN
        'price',         // 價格
        'stock',         // 庫存
        'cover_image',   // 封面圖片
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'publish_date' => 'datetime', // 出版日期需要轉換成 datetime
    ];
}

