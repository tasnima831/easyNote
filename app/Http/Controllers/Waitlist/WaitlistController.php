<?php

namespace App\Http\Controllers\Waitlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Waitlist\StoreWaitlistRequest;
use App\Services\Waitlist\WaitlistStorage;
use Illuminate\Http\JsonResponse;

class WaitlistController extends Controller
{
    public function store(StoreWaitlistRequest $request, WaitlistStorage $storage): JsonResponse
    {
        if (! $storage->append($request->validated())) {
            return response()->json(['message' => 'Could not save your interest. Please try again.'], 503);
        }

        return response()->json(['message' => 'You are on the list.'], 201);
    }
}
