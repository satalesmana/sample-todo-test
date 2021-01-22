<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request){
        $sort = $request->sort ? $request->sort : 'id';
        $order = $request->order == 'ascending' ? 'asc' : 'desc';
        $pageSize = $request->pageSize ? $request->pageSize : 10;

        $data = User::when($request->keyword, function($q) use ($request) {
            $q->where('name', 'LIKE', '%'.$request->keyword.'%')
                ->orWhere('email', 'LIKE', '%'.$request->keyword.'%');
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

    public function store(UserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $user = User::create($input);
        return ['message' => 'Data has been saved', 'data' => $user];
    }

    public function update(UserRequest $request, User $user)
    {
        $input = $request->all();

        if ($request->password) {
            $input['password'] = Hash::make($request->password);
        }

        $user->update($input);
        return ['message' => 'Data has been updated', 'data' => $user];
    }

    public function destroy(User $user)
    {
        $user->delete();
        return ['message' => 'Data has been deleted'];
    }

    public function login(Request $request){
        $user= User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.'],
            ], 422);
        }   

        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
    
         return response($response, 201);
    }
}
