<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() // Display stored tasks
    {
        $tasks = Task::all();

        if ($tasks) {
            return response()->json($tasks);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Resources not found'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) // Create task
    {
        $validator = Validator::make(
            $request->input(),
            ['description' => 'required']
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        } else {
            $task = new Task();
            $task->description = $request->description;

            if ($task->save()) {
                return response()->json([
                    'error' => false,
                    'message' => 'Resource created successfully'
                ], 201);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);

        if ($task) {
            return response()->json($task);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Resource not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) // Update task
    {
        $validator = Validator::make(
            $request->input(),
            ['description' => 'required']
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        } else {
            $task = Task::findOrFail($id);
            $task->description = $request->description;

            if ($task->save()) {
                return response()->json([
                    'error' => false,
                    'message' => 'Resource updated successfully'
                ], 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) // Delete task
    {
        $task = Task::findOrFail($id);

        if ($task->delete()) {
            return response()->json([
                'error' => false,
                'message' => 'Resource deleted successfully'
            ], 200);
        }
    }

    /**
     * Display the last specified resource id.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLastTaskId()
    {
        $lastTask = DB::select('select id from tasks order by id desc limit 1');

        if ($lastTask) {
            return response()->json($lastTask);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Resource not found'
            ], 404);
        }
    }

    /**
     * Complete a specific task
     *
     * @param int $id    
     * @return \Illuminate\Http\Response     
     */
    public function completeTask($id)
    {
        $task = Task::findOrFail($id);

        $task->completed_at = date("Y-m-d H:i:s");

        if ($task->save()) {
            return response()->json([
                'error' => false,
                'message' => 'Resource updated successfully'
            ], 200);
        }
    }

    /**
     * Uncomplete a specific task
     *
     * @param int $id    
     * @return \Illuminate\Http\Response     
     */
    public function uncompleteTask($id)
    {
        $task = Task::findOrFail($id);

        $task->completed_at = null;

        if ($task->save()) {
            return response()->json([
                'error' => false,
                'message' => 'Resource updated successfully'
            ], 200);
        }
    }
}
