<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resume;
use Illuminate\Http\JsonResponse;

class ResumeController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'data' => 'required|array'
        ]);

        $resume = Resume::updateOrCreate(
            ['user_id' => $user->id],
            ['data' => $data['data']]
        );

        return response()->json(['resume' => $resume], 201);
    }

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $resume = Resume::where('user_id', $user->id)->first();

        if (! $resume) {
            return response()->json(['resume' => null], 200);
        }

        return response()->json(['resume' => $resume], 200);
    }
}
