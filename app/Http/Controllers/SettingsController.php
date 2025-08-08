<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $request->validate(['key' => 'required|string']);
        $value = Setting::fromKey($request->input('key'));
        return response()->json(compact('value'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'nullable|string'
        ]);

        $setting = Setting::where('key', request('key'))->firstOrFail();
        $setting->update(['value' => request('value')]);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
