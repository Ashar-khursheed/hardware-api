<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcement = Announcement::where('status', 1)->latest()->first();
        return response()->json([
            'success' => true,
            'data' => $announcement
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'background_color' => 'nullable|string',
            'text_color' => 'nullable|string',
        ]);

        $announcement = Announcement::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully',
            'data' => $announcement
        ]);
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $announcement->update($request->only(['message', 'status', 'background_color', 'text_color']));

        return response()->json([
            'success' => true,
            'message' => 'Announcement updated',
            'data' => $announcement
        ]);
    }

    public function destroy($id)
    {
        Announcement::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted'
        ]);
    }
}
