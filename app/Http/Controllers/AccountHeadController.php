<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountHeadRequest;
use App\Models\AccountHead;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AccountHeadController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', 10);
        $keyword = Arr::get($searchParams, 'keyword', '');
        $accountHeadsQuery = AccountHead::with('group')
                                ->when(!empty($keyword), function ($query) use ($keyword) {
                                    return $query->where('name', 'LIKE', '%' . $keyword . '%');
                                });

        return response()->json($accountHeadsQuery->paginate($limit));
    }

    public function store(AccountHeadRequest $request)
    {
        try {
            $accountHeadData = $request->only('name', 'group_id');
            $accountHead = AccountHead::create($accountHeadData);
            return $accountHead;
        } catch (Exception $ex) {
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    

    public function edit($id)
    {
        $accountHead = AccountHead::with('group')->find($id);

        return response()->json($accountHead);
    }


    public function update(AccountHeadRequest $request, $id)
    {
        try {
            $accountHeadData = $request->only('name', 'group_id');
            $accountHead = AccountHead::find($id);
            $accountHead->update($accountHeadData);
            return $accountHead;
        } catch (Exception $ex) {
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    public function delete($id)
    {
        try {
            $accountHead = AccountHead::find($id);;
            $accountHead->delete();
            $accountHeads = AccountHead::all();
            return response()->json($accountHeads);
        } catch (Exception $ex) {
            return 'Delete Failed';
        }
    }

    
    public function getAllAccountHeads()
    {
        $accountHeads = AccountHead::all();
        return response()->json($accountHeads);
    }
}
