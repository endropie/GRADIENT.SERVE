<?php

namespace App\Http\Controllers\Api;

use App\Filters\Filter;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\ItemSerial;
use Illuminate\Http\Request;

class ItemController extends ApiController
{

    public function index (Request $request, Filter $filter)
    {
        switch ($request->get('mode')) {
            case 'all':
                $items = Item::filter($filter)->latest()->get();
                break;

            default:
                $items = Item::filter($filter)->pagetable();
                break;
        }

        return response()->json($items);
    }

    public function show ($id)
    {
        $item = Item::findOrFail($id);

        return response()->json($item);
        // return new ItemResource($item);
        // return ItemResource::collection(Item::all());
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
