<?php

namespace App\Http\Controllers;

use App\Models\TreeEntry;
use Illuminate\Http\Request;

class TreeController extends Controller
{
    //

    public function getTrees(Request $request){


        if($request->type=='all'){
            // this query run first time

            //with('child') child is TreeEntry function to getting child list of parent is work as recursive
        $data=TreeEntry::where('parent_entry_id',0)->with('child')->
        join('tree_entry_lang','tree_entry_lang.entry_id','tree_entry.entry_id')->orderBy('tree_entry_lang.name')
        ->get();
        }
        else{
            // this query run when with ajax button on and clicked any parent for child

            $data=TreeEntry::where('parent_entry_id',$request->id)
        ->join('tree_entry_lang','tree_entry_lang.entry_id','tree_entry.entry_id')->orderBy('tree_entry_lang.name')->select('tree_entry.entry_id','name') ->groupBy('tree_entry.entry_id','name')->withCount('child')
        ->get();

        }
        return response()->json($data);
    }
}
