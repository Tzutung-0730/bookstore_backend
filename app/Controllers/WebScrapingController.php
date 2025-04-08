<?php
namespace App\Controllers;

use App\Services\CrawlerService;
use Illuminate\Support\Facades\Session;

class WebScrapingController extends Controller
{
    protected $crawlerService;

    public function __construct(CrawlerService $crawlerService)
    {
        $this->crawlerService = $crawlerService;
    }

    /**
     * @OA\Get(
     *     path="/api/scrapebooks/ScrapeBooks",
     *     summary="顯示爬蟲進度頁面",
     *     tags={"ScrapeBooks"},
     *     @OA\Response(
     *         response=200,
     *         description="成功返回爬蟲進度頁面"
     *     )
     * )
     */
    public function ScrapeBooks()
    {
        return view('scraping-progress');
    }

    /**
     * @OA\Post(
     *     path="/api/scrapebooks/StartScraping",
     *     summary="啟動爬蟲",
     *     tags={"ScrapeBooks"},
     *     @OA\Response(
     *         response=200,
     *         description="爬蟲已啟動"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="爬蟲啟動失敗"
     *     )
     * )
     */
    public function StartScraping()
    {
        // 啟動爬蟲
        Session::put('scrape_stop', false);  // 確保爬蟲沒有停止
        $this->crawlerService->scrapeMorningstar();
        
        return response()->json(['message' => '爬蟲已經執行完成，書籍資料已儲存到資料庫']);
    }

    /**
     * @OA\Post(
     *     path="/api/scrapebooks/StopScraping",
     *     summary="停止爬蟲",
     *     tags={"ScrapeBooks"},
     *     @OA\Response(
     *         response=200,
     *         description="爬蟲已停止"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="停止爬蟲時發生錯誤"
     *     )
     * )
     */
    public function StopScraping()
    {
        // 停止爬蟲
        Session::put('scrape_stop', true);
        return response()->json(['message' => '爬蟲已停止']);
    }

    /**
     * @OA\Get(
     *     path="/api/scrapebooks/GetScrapeProgress",
     *     summary="獲取爬蟲進度",
     *     tags={"ScrapeBooks"},
     *     @OA\Response(
     *         response=200,
     *         description="成功返回爬蟲進度",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="progress", type="object", 
     *                 @OA\Property(property="current", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             ),
     *             @OA\Property(property="status", type="string", example="爬蟲正在進行中")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="獲取進度失敗"
     *     )
     * )
     */
    public function GetScrapeProgress()
    {
        // 返回進度
        return response()->json([
            'progress' => Session::get('scrape_progress', 0),
            'status' => Session::get('scrape_stop', false) ? '爬蟲已停止' : '爬蟲正在進行中'
        ]);
    }
}
