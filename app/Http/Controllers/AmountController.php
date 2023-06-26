<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AmountController extends Controller
{
    
    public function byAccountHead(Request $request)
    {
        $searchParams = $request->all();
        // $limit = Arr::get($searchParams, 'limit', 10);
        // $keyword = Arr::get($searchParams, 'keyword', '');
        $accountHeads = AccountHead::with('group.parent.parent')
                    ->leftJoin('transactions', 'transactions.account_head_id', '=', 'account_heads.id')
                    ->select('account_heads.id as id', 'name', 'group_id', 'transactions.id as transaction_id', DB::raw('SUM(debit-credit) as amount'))
                    ->groupBy('id')
                    ->get();
        
        $result = $accountHeads->map(function ($accountHead) {
            // dd($accountHead);
            if($accountHead->group->parent && $accountHead->group->parent->parent) {
                $level1GroupName = $accountHead->group->parent->parent->name;
                $level2GroupName = $accountHead->group->parent->name;
                $level3GroupName = $accountHead->group->name;
            }else if($accountHead->group->parent) {
                $level1GroupName = $accountHead->group->parent->name;
                $level2GroupName = $accountHead->group->name;
                $level3GroupName = null;
            }else {
                $level1GroupName = $accountHead->group->name;
                $level2GroupName = null;
                $level3GroupName = null;
            }
            return [
                'id' => $accountHead->id,
                'name' => $accountHead->name,
                'amount' => $accountHead->amount,
                'group_level_1_name' => $level1GroupName,
                'group_level_2_name' => $level2GroupName,
                'group_level_3_name' => $level3GroupName,
            ];
        });
        $result = $result->sortBy(['group_level_1_name', 'group_level_1_name'])->values()->all();
        return response()->json($result);
    }
    
    public function byGroup(Request $request)
    {
        $searchParams = $request->all();
        // $limit = Arr::get($searchParams, 'limit', 10);
        // $keyword = Arr::get($searchParams, 'keyword', '');
        $accountHeads = AccountHead::with('group.parent.parent')
                    ->leftJoin('transactions', 'transactions.account_head_id', '=', 'account_heads.id')
                    ->select('account_heads.id as id', 'name', 'group_id', 'transactions.id as transaction_id', DB::raw('SUM(debit-credit) as amount'))
                    ->groupBy('id')
                    ->get();
        
        $result = $accountHeads->map(function ($accountHead) {
            // dd($accountHead);
            $groupName = $accountHead->group->name;
            $level2GroupName = $accountHead->group->parent ? $accountHead->group->parent->name : null;
            $level3GroupName = $accountHead->group->parent && $accountHead->group->parent->parent ? $accountHead->group->parent->parent->name : null;
            return [
                'id' => $accountHead->id,
                'name' => $accountHead->name,
                'amount' => $accountHead->amount,
                'group_level_1_name' => $groupName,
                'group_level_2_name' => $level2GroupName,
                'group_level_3_name' => $level3GroupName,
            ];
        });
        
        return response()->json($result);
    }
}
