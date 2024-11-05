<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Класс для управления статьями
 */
class ProgressController extends Controller
{
    public function getProgressFromDB(Request $request): JsonResponse
    {

        // Получаем значение сессии
        $progress = session('save-to-db-progress');

        return response()->json(['progress' => $progress]);
    }
}
