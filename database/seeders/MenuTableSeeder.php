<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu')->insert([
            // 對所有人顯示
            ['title' => '首頁', 'url' => '/', 'icon' => 'pi-home', 'order' => 1, 'visible_for' => json_encode(['all'])],
            ['title' => '書籍總覽', 'url' => '/books', 'icon' => 'pi-book', 'order' => 2, 'visible_for' => json_encode(['all'])],
            ['title' => '關於我們', 'url' => '/about', 'icon' => 'pi-info', 'order' => 3, 'visible_for' => json_encode(['all'])],
            ['title' => '聯絡我們', 'url' => '/contact', 'icon' => 'pi-envelope', 'order' => 4, 'visible_for' => json_encode(['all'])],
            ['title' => '常見問題', 'url' => '/faq', 'icon' => 'pi-question', 'order' => 5, 'visible_for' => json_encode(['all'])],

            // 未登入者顯示（訪客）
            ['title' => '會員登入', 'url' => '/login', 'icon' => 'pi-sign-in', 'order' => 1, 'visible_for' => json_encode(['guest'])],
            ['title' => '加入會員', 'url' => '/register', 'icon' => 'pi-user-plus', 'order' => 2, 'visible_for' => json_encode(['guest'])],

            // 登入使用者才顯示
            ['title' => '會員專區', 'url' => '/profile', 'icon' => 'pi-user', 'order' => 1, 'visible_for' => json_encode(['user'])],
            ['title' => '購物車', 'url' => '/cart', 'icon' => 'pi-shopping-cart', 'order' => 2, 'visible_for' => json_encode(['user'])],
            ['title' => '訂單查詢', 'url' => '/orders', 'icon' => 'pi-list', 'order' => 3, 'visible_for' => json_encode(['user'])],
            ['title' => '我的收藏', 'url' => '/favorites', 'icon' => 'pi-heart', 'order' => 4, 'visible_for' => json_encode(['user'])],
            ['title' => '會員登出', 'url' => '/logout', 'icon' => 'pi-sign-out', 'order' => 10, 'visible_for' => json_encode(['user', 'admin'])],

            // 管理員才顯示
            ['title' => '管理者首頁', 'url' => '/admin/dashboard', 'icon' => 'pi-dashboard', 'order' => 1, 'visible_for' => json_encode(['admin'])],
            ['title' => '書籍管理', 'url' => '/admin/books', 'icon' => 'pi-pencil', 'order' => 2, 'visible_for' => json_encode(['admin'])],
            ['title' => '訂單管理', 'url' => '/admin/orders', 'icon' => 'pi-briefcase', 'order' => 3, 'visible_for' => json_encode(['admin'])],
            ['title' => '使用者管理', 'url' => '/admin/users', 'icon' => 'pi-users', 'order' => 4, 'visible_for' => json_encode(['admin'])],
            ['title' => '選單管理', 'url' => '/admin/menu', 'icon' => 'pi-menu', 'order' => 5, 'visible_for' => json_encode(['admin'])]
        ]);
    }
}

