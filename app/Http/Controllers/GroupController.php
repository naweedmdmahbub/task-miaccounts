<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', 10);
        $keyword = Arr::get($searchParams, 'keyword', '');
        $groupsQuery = Group::with(['parent' => function ($query) {
                                $query->select('id', 'name');
                            }])
                            ->select('name', 'id', 'parent_id')
                            ->when(!empty($keyword), function ($query) use ($keyword) {
                                return $query->where('name', 'LIKE', '%' . $keyword . '%');
                            });

        return response()->json($groupsQuery->paginate($limit));
    }

    public function store(GroupRequest $request)
    {
        try {
            $groupData = $request->only('name', 'parent_id');
            $group = Group::create($groupData);
            return $group;
        } catch (Exception $ex) {
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    

    public function edit($id)
    {
        $group = Group::with('parent')->find($id);
        return response()->json($group);
    }


    public function update(GroupRequest $request, $id)
    {
        try {
            $groupData = $request->only('name', 'parent_id');
            $group = Group::find($id);
            $group->update($groupData);
            return $group;
        } catch (Exception $ex) {
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    public function delete($id)
    {
        try {
            $group = Group::find($id);;
            $group->delete();
            $groups = Group::all();
            return response()->json($groups);
        } catch (Exception $ex) {
            return 'Delete Failed';
        }
    }

    
    public function getAllGroups()
    {
        $groups = Group::all();
        return response()->json($groups);
    }
}
