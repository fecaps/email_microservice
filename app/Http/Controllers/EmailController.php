<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailPost;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreEmailPost  $request
     * @return JsonResponse
     */
    public function store(StoreEmailPost $request): JsonResponse
    {
        return response()->json($request->all());
    }
}
