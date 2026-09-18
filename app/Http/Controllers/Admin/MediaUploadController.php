<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $path = 'media/'.date('Y/m').'/'.Str::uuid().'.'.$ext;

        Storage::disk('spaces')->putFileAs('', $file, $path, ['visibility' => 'public']);

        return response()->json([
            'url' => Storage::disk('spaces')->url($path),
            'path' => $path,
        ]);
    }
}
