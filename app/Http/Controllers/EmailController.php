<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreEmailPost;
use App\Model\Email;

class EmailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmailPost  $request
     * @param Email  $model
     * @return JsonResponse
     */
    public function store(StoreEmailPost $request, Email $model): JsonResponse
    {
        $email = $request->all();
        $emailStored = $model->storeEmail($email);

        return response()
            ->json([ 'data' => $emailStored ])
            ->setStatusCode(201);
    }
}
