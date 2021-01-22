<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodosRequest;
use App\Models\Todos;
use App\Models\Comments;
use Illuminate\Http\Request;

class TodosController extends Controller
{
    public function index(Request $request){
        $sort = $request->sort ? $request->sort : 'id';
        $order = $request->order == 'ascending' ? 'asc' : 'desc';
        $pageSize = $request->pageSize ? $request->pageSize : 10;

        $data = Todos::when($request->keyword, function($q) use ($request) {
            $q->where('name', 'LIKE', '%'.$request->keyword.'%');
        })
        ->with('comments')->orderBy($sort, $order)->paginate($pageSize);

        return [
            'total' => $data->total(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem(),
            'data' => $data->map(function ($d) {
                return array_merge($d->toArray(), [
                    'created_at' => ($d->created_at)? $d->created_at->format('d-M-Y H:i'):'',
                    'updated_at' => ($d->updated_at)? $d->updated_at->format('d-M-Y H:i'):'',
                ]);
            })
        ];
    }

    public function store(TodosRequest $request)
    {
        $input = $request->all();
        $todo = Todos::create($input);

        return ['message' => 'Data has been saved', 'data' => $todo];
    }

    public function update(TodosRequest $request, Todos $todo)
    {
        $input = $request->all();

        $todo->update($input);
        return ['message' => 'Data has been updated', 'data' => $todo];
    }

    public function destroy(Todos $todo)
    {
        $todo->delete();
        return ['message' => 'Data has been deleted'];
    }
}
