<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'source' => 'nullable|string',
        ]);

        $review = Review::create([
            'user_id' => $user->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'source' => $data['source'] ?? null,
        ]);

        return response()->json(['review' => $review], 201);
    }
}
