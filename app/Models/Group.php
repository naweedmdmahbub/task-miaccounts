<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'parent_id'];
    public function subGroups(){
        return $this->hasMany(Group::class, 'parent_id', 'id');
    }
    public function parentGroup(){
        return $this->belongsTo(Group::class, 'parent_id', 'id');
    }
}
