<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use App\Models\Group;
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
        $result = $result->sortBy(['group_level_1_name'])->values()->all();
        return response()->json($result);
    }



    public function byGroup(Request $request)
    {
        $searchParams = $request->all();
        // $limit = Arr::get($searchParams, 'limit', 10);
        // $keyword = Arr::get($searchParams, 'keyword', '');
        // $groups = Group::with('subgroups.accountHeads')->where('parent_id', null)->get();
        // $groups = Group::with('accountHeads.transactions', 'subgroups', 'subgroups.accountHeads.transactions', 
        //                     'subgroups.subgroups', 'subgroups.subgroups.accountHeads.transactions')
        //                 // ->leftJoin('account_heads', 'account_heads.group_id', '=', 'groups.id')
        //                 // ->leftJoin('transactions', 'transactions.account_head_id', '=', 'account_heads.id')
        //                 // ->select('groups.id as id', 'groups.name', 
        //                 //         'account_heads.id as account_head_id',
        //                 //         'account_heads.group_id',
        //                 //          'transactions.id as transaction_id', DB::raw('SUM(debit-credit) as amount'))
        //                 ->whereNull('parent_id')->get();

        $groups = Group::with('accountHeads.transactions', 'allSubgroups', 'allSubgroups.accountHeads')
                    ->whereNull('parent_id')
                    ->get();
        // return $groups;


        $result = $groups->map(function ($group) {
            //group 1
            $group->accountHeads->map(function($accountHead){
                $amount = $accountHead->transactions->sum('debit') - $accountHead->transactions->sum('credit');
                $accountHead->amount = $amount;
                $accountHead->level = 1;
                unset($accountHead->transactions);
                // dd($accountHead);
            });


            // dump($groupAmount);
            //group 1
            $group->allSubgroups->map(function($subgroup){
                if($subgroup->accountHeads){
                    $subgroup->accountHeads->map(function($accountHead){
                        $amount = $accountHead->transactions->sum('debit') - $accountHead->transactions->sum('credit');
                        $accountHead->amount = $amount;
                        $accountHead->level = 2;
                        unset($accountHead->transactions);
                    });
                }
                //group 2,6
                if($subgroup->allSubgroups){
                    $subgroup->allSubgroups->map(function($subgroup){
                        if($subgroup->accountHeads){
                            $subgroup->accountHeads->map(function($accountHead){
                                $amount = $accountHead->transactions->sum('debit') - $accountHead->transactions->sum('credit');
                                $accountHead->amount = $amount;
                                $accountHead->level = 3;
                                unset($accountHead->transactions);
                            });
                        }

                        //group 4
                        if($subgroup->allSubgroups){
                            $subgroup->allSubgroups->map(function($subgroup){
                                if($subgroup->accountHeads){
                                    $subgroup->accountHeads->map(function($accountHead){
                                        $amount = $accountHead->transactions->sum('debit') - $accountHead->transactions->sum('credit');
                                        $accountHead->amount = $amount;
                                        unset($accountHead->transactions);
                                    });
                                }
                            });
                        }
                        $subgroup->level = 2;
                        $subgroup->amount = $subgroup->accountHeads->sum('amount') + $subgroup->allSubgroups->sum('amount');
                    });
                    $subgroup->level = 1;
                }
                // $subgroup->amount = $subgroup->accountHeads->sum('amount');
                $subgroup->amount = $subgroup->accountHeads->sum('amount') + $subgroup->allSubgroups->sum('amount');
                $subgroup->level = 1;
                // dd($subgroup);
            });
            // dd($group->accountHeads, $group);
            $group->amount = $group->accountHeads->sum('amount') + $group->allSubgroups->sum('amount');
                // if($group->id == 5)
                // dd($group);
            
            return [
                'id' => $group->id,
                'name' => $group->name,
                'amount' => $group->amount,
                'level' => 0,
                'accountHeads' => $group->accountHeads,
                'allSubgroups' => $group->allSubgroups,
            ];
        });
        return $result;
    }
}
