<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // ✅ عرض كل المهام للمستخدم الحالي
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', $request->user()->id)->get();

        return response()->json([
            'status' => true,
            'message' => 'List of tasks',
            'data' => $tasks
        ]);
    }

    // ✅ إنشاء مهمة جديدة 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'is_done' => 'required|boolean',
        ]);

        $task = Task::create([
            'title'   => $validated['title'],
            'is_done' => $validated['is_done'],
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    // ✅ عرض تفاصيل مهمة محددة
    public function show(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Task details',
            'data' => $task
        ]);
    }

    // ✅ تحديث مهمة محددة
    public function update(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $validated = $request->validate([
            'title'   => 'sometimes|required|string|max:255',
            'is_done' => 'sometimes|required|boolean',
        ]);

        $task->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Task updated successfully',
            'data' => $task
        ]);
    }

    // ✅ حذف مهمة محددة
    public function destroy(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'status' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}
