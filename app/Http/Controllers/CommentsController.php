<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentsRequest;
use App\Models\Comments;
use App\Models\Todos;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index(Request $request){
        $sort = $request->sort ? $request->sort : 'id';
        $order = $request->order == 'ascending' ? 'asc' : 'desc';
        $pageSize = $request->pageSize ? $request->pageSize : 10;

        $data = Comments::when($request->keyword, function($q) use ($request) {
            $q->where('name', 'LIKE', '%'.$request->keyword.'%');
        })->orderBy($sort, $order)->paginate($pageSize);

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

    public function store(CommentsRequest $request)
    {
        $todos = Todos::find($request->todos_id);
        $comment = $request->all();
        $todos->comments()->create($comment);

        return ['message' => 'Data has been saved', 'data' => $comment];
    }

    public function update(CommentsRequest $request, Comments $comment)
    {
        $input = $request->all();

        $comment->update($input);
        return ['message' => 'Data has been updated', 'data' => $comment];
    }

    public function destroy(Comments $comment)
    {
        $comment->delete();
        return ['message' => 'Data has been deleted'];
    }
}
