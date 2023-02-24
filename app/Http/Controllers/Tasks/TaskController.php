<?php

namespace App\Http\Controllers\Tasks;

use App\Facades\UploadFacade\Facades\UploadFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\AddNewTask;
use App\Http\Requests\Task\UpdateTask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->ResponseJson(
            Auth::user()->tasks
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $request;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddNewTask $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'desc' => $request->desc,
            'user_id' => Auth::user()->id,
        ]);

        if ($request->file) {
            $task->file =   UploadFacade::uploadFile($request->file, "tasks/{$task->id}");
            $task->save();
        }

        return $this->ResponseJson($task, 200, 'created');
        //     return UploadFacade::deleteFile($file);
        // return $_SERVER['HTTP_HOST'];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTask $request, Task $task)
    {
        if (!Gate::allows('update', $task)) {
            return $this->ResponseJson(null, 401, 'Access denied');
        }

        $updateData = [
            'title' => $task->title,
            'desc' => $task->desc,
            'file' => $task->file,
            'completed' => $task->completed
        ];

        foreach ($updateData as $key => $value) {
            if (!isset($request->{$key})) {
                continue;
            }


            switch ($key) {
                case 'file':
                    if ($task->file) {
                        UploadFacade::deleteFile($task->file);
                    }

                    $updateData['file'] = UploadFacade::uploadFile($request->file, "tasks/{$task->id}");
                    break;
                case 'completed':
                    $updateData['completed'] = boolval($request->completed) ? 1 : 0;
                    break;

                default:
                    $updateData[$key] = $request->{$key};
                    break;
            }
        }

        $task->update($updateData);


        return $this->ResponseJson($task, 200, 'updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {

        if (!Gate::allows('delete', $task)) {
            return $this->ResponseJson(null, 401, 'Access denied');
        }
        if ($task->delete()) {
            return $this->ResponseJson(null, 200, 'deleted');
        }

        return $this->ResponseJson(null, 500, 'server error');
    }
}
