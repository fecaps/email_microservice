<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailPost;
use App\Publishers\EmailPublisher;
use App\Queue;
use App\Enum\LogMessages;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmailPost  $request
     * @param EmailPublisher  $publisher
     * @param Queue  $queue
     * @return JsonResponse
     */
    public function store(
        StoreEmailPost $request,
        EmailPublisher $publisher,
        Queue $queue
    ): JsonResponse {
        \Log::channel('publisher')->info(LogMessages::START_WEB);

        $data = $request->all();
        $id = $queue->addToQueue($data);
        $fullData = array_merge($data, [ 'id' => $id ]);

        $publisher->handle($fullData);

        return response()
            ->json([ 'data' => $fullData ])
            ->setStatusCode(201);
    }
}
