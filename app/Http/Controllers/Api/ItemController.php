<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemSerial;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function index (Request $request)
    {

        $items = Item::all();

        return response()->json($items);
    }

    public function show ($id)
    {
        $item = Item::findOrFail($id);

        return response()->json($item);
    }

    public function destroy ($id)
    {
        $item = Item::findOrFail($id);

        // $item->delete();

        return response()->json(['success' => true]);
    }

    public function serial ($id)
    {
        $item = ItemSerial::with(['item'])->where('serial', $id)->first();

        if (!$item) abort(404);

        return response()->json($item);
    }
}
