<?php

namespace App\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/menu/GetMenu",
     *     summary="取得所有菜單項目",
     *     tags={"Menu"},
     *     @OA\Response(
     *         response=200,
     *         description="成功取得菜單",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="首頁"),
     *                 @OA\Property(property="url", type="string", example="/Home"),
     *                 @OA\Property(property="icon", type="string", example="pi-home"),
     *                 @OA\Property(property="order", type="integer", example=1),
     *                 @OA\Property(property="visible_for", type="array", items=@OA\Items(type="string"), example={"user", "admin"})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="驗證錯誤"
     *     )
     * )
     */
    public function GetMenu()
    {
        $menu = Menu::orderBy('menu_id','asc')->get();
        return response()->json($menu);
    }

    /**
     * @OA\Post(
     *     path="/api/menu/CreateMenu",
     *     summary="新增一筆 Menu",
     *     tags={"Menu"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="新增菜單所需資料",
     *         @OA\JsonContent(
     *             required={"title", "url"},
     *             @OA\Property(property="title", type="string", example="首頁"),
     *             @OA\Property(property="url", type="string", example="/Home"),
     *             @OA\Property(property="icon", type="string", example="pi-home"),
     *             @OA\Property(property="order", type="integer", example=1),
     *             @OA\Property(property="visible_for", type="array", items=@OA\Items(type="string"), example={"user", "admin"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="新增成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="menu_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="首頁"),
     *             @OA\Property(property="url", type="string", example="/Home"),
     *             @OA\Property(property="icon", type="string", example="pi-home"),
     *             @OA\Property(property="order", type="integer", example=1),
     *             @OA\Property(property="visible_for", type="array", items=@OA\Items(type="string"), example={"user", "admin"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="驗證錯誤"
     *     )
     * )
     */
    public function CreateMenu(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'url' => 'required|string',
            'icon' => 'nullable|string',
            'order' => 'integer',
            'visible_for' => 'required|array', // 接受一個陣列
            'visible_for.*' => 'in:all,guest,user,admin' // 確保每個值都在這些選項中
        ]);
        $validatedData['visible_for'] = json_encode($validatedData['visible_for']);
        $menu = Menu::create($validatedData);
        return response()->json($menu,201);
    }

    /**
     * @OA\Put(
     *     path="/api/menu/UpdateMenu/{id}",
     *     summary="更新特定 Menu",
     *     tags={"Menu"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Menu 的 ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="更新資料",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="回首頁"),
     *             @OA\Property(property="url", type="string", example="/Home"),
     *             @OA\Property(property="icon", type="string", example="pi-home"),
     *             @OA\Property(property="order", type="integer", example=1),
     *             @OA\Property(property="visible_for", type="array", items=@OA\Items(type="string"), example={"user"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="更新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="menu_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="回首頁"),
     *             @OA\Property(property="url", type="string", example="/Home"),
     *             @OA\Property(property="icon", type="string", example="pi-home"),
     *             @OA\Property(property="order", type="integer", example=1),
     *             @OA\Property(property="visible_for", type="array", items=@OA\Items(type="string"), example={"user"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="驗證錯誤"
     *     )
     * )
     */
    
    public function UpdateMenu(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'url' => 'required|string',
            'icon' => 'nullable|string',
            'order' => 'integer',
            'visible_for' => 'required|array',
            'visible_for.*' => 'in:all,guest,user,admin'
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update($validatedData);
        return response()->json($menu);
    }

    /**
     * @OA\Delete(
     *     path="/api/menu/DeleteMenu/{id}",
     *     summary="刪除特定 Menu",
     *     tags={"Menu"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Menu 的 ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="刪除成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="刪除成功")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="找不到指定 Menu"
     *     )
     * )
     */
    public function DeleteMenu(string $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return response()->json(['message'=>'刪除成功']);
    }
}
