<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return view('codes.index');
    }
    public function list(Request $request)
    {
        $search = $request->input('q') ?? '';
        $items = Item::where('name', 'LIKE', "%$search%")->select('id', 'name')->get();

        return response()->json($items);
    }
}
