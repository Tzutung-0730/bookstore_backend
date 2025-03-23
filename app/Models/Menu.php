<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $timestamps = false;
    protected $table = 'menu'; // 指定資料表名稱

    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'menu_id',
        'title',
        'url',
        'icon',
        'order',
        'visible_for',
    ];
}
