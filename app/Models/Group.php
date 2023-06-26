<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'parent_id'];
    public function subgroups(){
        return $this->hasMany(Group::class, 'parent_id', 'id');
    }
    public function parent(){
        return $this->belongsTo(Group::class, 'parent_id', 'id');
    }

    public function accountHeads(){
        return $this->hasMany(AccountHead::class);
    }
    
    public function allSubgroups()
    {
        return $this->subgroups()->with('allSubgroups', 'accountHeads.transactions');
    }
}
