<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AmountController extends Controller
{
    
    public function byAccountHead()
    {
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



    public function byGroup()
    {
        $groups = Group::with('accountHeads.transactions', 'allSubgroups', 'allSubgroups.accountHeads.transactions')
                    ->whereNull('parent_id')
                    ->get();

        $results = $groups->map(function ($group){
            //group 1
            $group->allSubgroups->map(function($subgroup){
                $this->processSubGroup($subgroup, 1);
            });

            //group 1
            $group->accountHeads->map(function($accountHead){
                $accountHead = $this->processAccountHead($accountHead, 1);
                // dd($accountHead);
            });
            $group->amount = $group->accountHeads->sum('amount') + $group->allSubgroups->sum('amount');
            // dd($group->accountHeads, $group);
                // if($group->id == 5)  dd($group);
            return [
                'id' => $group->id,
                'name' => $group->name,
                'amount' => $group->amount,
                'level' => 0,
                'accountHeads' => $group->accountHeads,
                'allSubgroups' => $group->allSubgroups,
            ];
        });
        


        $items = [];
        $results->each(function($result) use(&$items){
            //getting level 0 group
            $items[] = [
                'group' => $result['name'],
                'group_head' => null,
                'level' => $result['level'],
                'amount' => $result['amount'],
            ];
            $result['allSubgroups']->each(function($subgroup) use(&$items){
                //getting level 1 subgroup
                $items[] = [
                    'group' => null,
                    'group_head' => $subgroup->name,
                    'level' => $subgroup->level,
                    'amount' => $subgroup->amount,
                ];
                $subgroup['allSubgroups']->each(function($subgroup) use(&$items){
                    //getting level 2 subgroup
                    $items[] = [
                        'group' => null,
                        'group_head' => $subgroup->name,
                        'level' => $subgroup->level,
                        'amount' => $subgroup->amount,
                    ];
                    //getting level 3 accountHeads
                    if(count($subgroup['accountHeads'])){
                        $subgroup['accountHeads']->each(function($accountHead) use(&$items){
                            $items[] = [
                                'group' => null,
                                'group_head' => $accountHead->name,
                                'level' => $accountHead->level,
                                'amount' => $accountHead->amount,
                            ];
                        });
                    }
                });
                //getting level 2 accountHeads
                $subgroup['accountHeads']->each(function($accountHead) use(&$items){
                    $items[] = [
                        'group' => null,
                        'group_head' => $accountHead->name,
                        'level' => $accountHead->level,
                        'amount' => $accountHead->amount,
                    ];
                });
            });

            $result['accountHeads']->each(function($accountHead) use(&$items){
                //getting level 2 allSubgroups
                $items[] = [
                    'group' => null,
                    'group_head' => $accountHead->name,
                    'level' => $accountHead->level,
                    'amount' => $accountHead->amount,
                ];
            });
        });

        return $items;
    }


    public function processSubGroup($subgroup, $level)
    {
        //group 2,6
        if($subgroup->allSubgroups){
            $subgroup->allSubgroups->map(function($subgroup) use($level){
                //group 4
                $subgroup = $this->processSubGroup($subgroup, $level+1);
            });
        }
        if($subgroup->accountHeads){
            $subgroup->accountHeads->map(function($accountHead) use($level){
                $this->processAccountHead($accountHead, $level+1);
            });
        }
        // $subgroup->amount = $subgroup->accountHeads->sum('amount');
        $subgroup->amount = $subgroup->accountHeads->sum('amount') + $subgroup->allSubgroups->sum('amount');
        $subgroup->level = $level;
        // dd($subgroup);
        return $subgroup;
    }

    public function processAccountHead($accountHead, $level)
    {
        $amount = $accountHead->transactions->sum('debit') - $accountHead->transactions->sum('credit');
        $accountHead->amount = $amount;
        $accountHead->level = $level;
        unset($accountHead->transactions);
    }
}
