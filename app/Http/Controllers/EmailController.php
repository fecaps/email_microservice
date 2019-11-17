<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailPost;
use App\Publishers\EmailPublisher;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreEmailPost  $request
     * @param  EmailPublisher  $publisher
     * @return JsonResponse
     */
    public function store(
        StoreEmailPost $request,
        EmailPublisher $publisher
    ): JsonResponse {
        \Log::channel('publisher')->info('New message requested - Web');
        $publisher->handle($request->all());

        return response()
            ->json([ 'data' => $request->all()])
            ->setStatusCode(201);
    }
}
