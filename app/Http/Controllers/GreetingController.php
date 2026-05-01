<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreGreetingRequest;
use App\Http\Resources\GreetingResource;
use App\Services\GreetingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class GreetingController extends Controller
{
    public function __construct(
        private readonly GreetingService $greetingService,
    ) {}

    /**
     * Store a new greeting
     */
    public function store(StoreGreetingRequest $request): JsonResponse
    {
        $greeting = $this->greetingService->createGreeting(
            name: $request->validated('name'),
            message: $request->validated('message'),
            bgColor: $request->validated('bg_color'),
            image: $request->file('image'),
        );

        return response()->json(
            new GreetingResource($greeting),
            201,
        );
    }

    /**
     * Get all greetings
     */
    public function index(): ResourceCollection
    {
        return GreetingResource::collection(
            $this->greetingService->getAllGreetings(),
        );
    }

    /**
     * Get a specific greeting
     */
    public function show(int $id): JsonResponse
    {
        $greeting = $this->greetingService->getGreeting($id);

        return response()->json(
            new GreetingResource($greeting),
        );
    }
}
