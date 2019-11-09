<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailPost;
use App\Transactors\MailjetTransactor;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreEmailPost  $request
     * @param  MailjetTransactor  $transactor
     * @return JsonResponse
     */
    public function store(
        StoreEmailPost $request,
        MailjetTransactor $transactor
    ): JsonResponse {
        $payload = $transactor->preparePayload($request->all());

        $payload->send();

        return response()->json($request->all());
    }
}
