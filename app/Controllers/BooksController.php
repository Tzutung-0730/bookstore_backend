<?php

namespace App\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * 取得所有書籍並進行分頁
     *
     * @OA\Get(
     *     path="/api/books/GetBooks",
     *     summary="取得所有書籍",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="分頁的頁碼",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功返回書籍資料",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="book_id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="書籍標題"),
     *                     @OA\Property(property="author", type="string", example="作者名稱"),
     *                     @OA\Property(property="publisher", type="string", example="出版商名稱"),
     *                     @OA\Property(property="publish_date", type="string", example="2022-01-01"),
     *                     @OA\Property(property="category", type="string", example="小說"),
     *                     @OA\Property(property="description", type="string", example="簡短描述"),
     *                     @OA\Property(property="isbn", type="string", example="1234567890"),
     *                     @OA\Property(property="price", type="decimal", example=19.99),
     *                     @OA\Property(property="cover_image", type="string", example="https://example.com/cover.jpg")
     *                 )
     *             ),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", example="http://example.com/api/books?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://example.com/api/books?page=5"),
     *                 @OA\Property(property="prev", type="string", example="http://example.com/api/books?page=2"),
     *                 @OA\Property(property="next", type="string", example="http://example.com/api/books?page=4")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="錯誤的請求，頁碼參數無效"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="無書籍資料"
     *     )
     * )
     */
    public function GetBooks(Request $request)
    {
        $page = $request->query('page', 1);  // 默認頁碼是 1

        // 使用分頁來獲取書籍資料
        $books = Book::paginate(10, ['*'], 'page', $page);  // 每頁顯示 10 本書

        // 返回分頁結果作為 JSON 格式
        return response()->json($books);
    }


    /**
     * 根據 ISBN 獲取單本書籍詳細資料
     *
     * @OA\Get(
     *     path="/api/books/GetBookByISBN/{isbn}",
     *     summary="根據 ISBN 獲取單本書籍",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="isbn",
     *         in="path",
     *         description="書籍的 ISBN",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功返回書籍詳細資料",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="book_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Book Title"),
     *             @OA\Property(property="author", type="string", example="Author Name"),
     *             @OA\Property(property="publisher", type="string", example="Publisher Name"),
     *             @OA\Property(property="publish_date", type="string", example="2022-01-01"),
     *             @OA\Property(property="category", type="string", example="Fiction"),
     *             @OA\Property(property="description", type="string", example="Full description of the book"),
     *             @OA\Property(property="isbn", type="string", example="1234567890"),
     *             @OA\Property(property="price", type="decimal", example=19.99),
     *             @OA\Property(property="cover_image", type="string", example="https://example.com/image.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="書籍未找到"
     *     )
     * )
     */
    public function GetBookByISBN($isbn)
    {
        // 根據 ISBN 查找書籍
        $book = Book::where('isbn', $isbn)->first();

        // 如果書籍不存在，返回 404
        if (!$book) {
            return response()->json(['message' => '書籍未找到'], 404);
        }

        // 返回書籍詳細資料
        return response()->json($book);
    }
}
