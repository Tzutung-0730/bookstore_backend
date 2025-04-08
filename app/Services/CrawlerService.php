<?php

namespace App\Services;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use App\Models\Book;
use Illuminate\Support\Facades\Session;

class CrawlerService
{
    public function scrapeMorningstar()
    {
        set_time_limit(0);

        // 先啟動 Selenium Server（這會開啟一個新的終端機視窗執行命令）
        $command = 'start cmd /k "java -Dwebdriver.chrome.driver="../chromedriver-win64/chromedriver.exe" -jar ../selenium-server-4.30.0.jar standalone"';
        
        // 執行命令
        exec($command, $output, $returnVar);

        // 檢查命令是否成功執行
        if ($returnVar !== 0) {
            echo "無法啟動 Selenium Server\n";
            return;
        }

        echo "Selenium Server 已啟動\n";

        // 設置 Chrome 驅動的選項
        $options = new ChromeOptions();
        $options->addArguments(["--disable-gpu", "--window-size=1920x1080"]);

        // 設置 WebDriver 位置和選項
        try {
            echo "初始化 WebDriver\n";
            $driver = RemoteWebDriver::create(
                'http://localhost:4444', // Selenium 伺服器的地址
                $options->toCapabilities()
            );
        } catch (\Exception $e) {
            echo "無法連接到 Selenium 伺服器: " . $e->getMessage() . "\n";
            return;
        }

        $baseUrl = 'https://www.morningstar.com.tw'; // 網站基礎 URL
        $currentPage = 1; // 當前頁數

        while (true) {
            // 抓取當前頁面
            echo "抓取頁面: {$baseUrl}/bibliographiclist.aspx?bcid=54&page={$currentPage}\n";
            $driver->get("{$baseUrl}/bibliographiclist.aspx?bcid=54&page={$currentPage}");

            // 等待頁面元素加載
            sleep(3);  // 根據實際需要進行調整
            echo "頁面加載完成\n";

            // 找到書籍列表
            $elements = $driver->findElements(WebDriverBy::cssSelector('#SearchData li'));
            $bookUrls = [];
            foreach ($elements as $element) {
                try {
                    $bookLink = 'https://www.morningstar.com.tw' . $element->findElement(WebDriverBy::cssSelector('a'))->getAttribute('href');
                    $bookUrls[] = $bookLink;
                    echo "抓取書籍 URL: {$bookLink}\n";
                } catch (\Exception $e) {
                    echo "無法抓取書籍 URL: " . $e->getMessage() . "\n";
                    continue;
                }
            }
            
            $total = count($elements); // 總書籍數量
            echo "總共抓取到 {$total} 本書籍\n";
            $count = 0; // 已爬取書籍數量

            foreach ($bookUrls as $bookLink) {
                // 檢查是否有停止信號
                if (Session::get('scrape_stop', false)) {
                    break;
                }

                $count++;
                Session::put('scrape_progress', [
                    'current' => $count,
                    'total' => $totalBooks,
                ]);
                echo "爬蟲進度: {$count} / {$total}\n";

                // 進入書籍詳細頁面
                $driver->get($bookLink);

                // 等待頁面元素加載
                sleep(3);  // 根據實際需要進行調整

                $title = $driver->findElement(WebDriverBy::cssSelector('.BookName #BookName'))->getText();
                echo "書名: {$title}\n";

                $author = $driver->findElement(WebDriverBy::cssSelector('#Author a'))->getText();
                echo "作者: {$author}\n";

                $publisher = $driver->findElement(WebDriverBy::cssSelector('#Press a'))->getText();
                echo "出版社: {$publisher}\n";

                $publish_date = $driver->findElement(WebDriverBy::cssSelector('#FDate'))->getText();
                $publish_date = str_replace('初版日期：', '', $publish_date); 
                echo "出版日期: {$publish_date}\n";

                $category = $driver->findElement(WebDriverBy::cssSelector('#BClass a'))->getText();
                echo "分類: {$category}\n";

                $description_element = $driver->findElement(WebDriverBy::cssSelector('div[name="bf187"] .Contents'));
                $description = $description_element->getText();
                echo "內容簡介: \n" . $description . "\n";

                $isbn = $driver->findElement(WebDriverBy::cssSelector('#ISBN'))->getText();
                $isbn = str_replace('ISBN：', '', $isbn); 
                echo "ISBN: {$isbn}\n";

                $price = $driver->findElement(WebDriverBy::cssSelector('#Price'))->getText();
                echo "價格: {$price}\n";

                $cover_image = $driver->findElement(WebDriverBy::cssSelector('.BookImg img'))->getAttribute('src');
                echo "封面圖片URL: {$cover_image}\n";
                
                try {
                    Book::create([
                        'title' => $title,
                        'author' => $author,
                        'publisher' => $publisher,
                        'publish_date' => $publish_date,
                        'category' => $category,
                        'description' => $description,
                        'isbn' => $isbn,
                        'price' => $price,
                        'cover_image' => $cover_image,
                    ]);
                    echo "書籍資料已儲存\n";
                } catch (\Exception $e) {
                    echo "儲存書籍資料時出錯: " . $e->getMessage() . "\n";
                }

                // 儲存爬取資料到 Session
                Session::push('scraping_data', [
                    'title' => $title,
                    'author' => $author,
                    'publisher' => $publisher,
                    'publish_date' => $publish_date,
                    'category' => $category,
                    'description' => $description,
                    'isbn' => $isbn,
                    'price' => $price,
                    'cover_image' => $cover_image,
                ]);
                echo "爬取資料已儲存到 Session\n";

                // 檢查是否存在下一頁
                $nextPageButton = $driver->findElement(WebDriverBy::cssSelector('.btnNext a'));
                if ($nextPageButton) {
                    $currentPage++;
                    echo "換到下一頁: {$currentPage}\n";
                } else {
                    break; // 沒有下一頁，停止抓取
                }
            }

            // 關閉 WebDriver
            echo "爬取完成，關閉 WebDriver\n";
            $driver->quit();
        }
    }
}
