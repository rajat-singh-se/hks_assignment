<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreeEntry extends Model
{
    use HasFactory;
    protected $table = 'tree_entry';

    public function child(){
        return $this->hasMany(TreeEntry::class,'parent_entry_id','entry_id')->join('tree_entry_lang','tree_entry_lang.entry_id','tree_entry.entry_id')
        ->orderBy('tree_entry_lang.name')->with('child');
    }
            //child hasMany function to getting child list of parent is work as recursive


    public function tree_name(){
        return $this->hasOne(TreeEntryLang::class,'entry_id','entry_id');
    }
}
