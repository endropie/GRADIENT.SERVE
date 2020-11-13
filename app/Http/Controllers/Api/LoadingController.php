<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\LoadingResource;
use App\Models\Item;
use App\Models\Loading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadingController extends ApiController
{
    public function index (Request $request)
    {
        switch ($request->get('mode')) {
            case 'all':
                $loadings = Loading::latest()->limitable();
                $loadings = LoadingResource::collection($loadings);
                break;

            default:
                $loadings = Loading::latest()->pagetable();
                $loadings->getCollection()->transform(function($row) {
                    return new LoadingResource($row);
                });
                break;
        }

        return response()->json($loadings);
    }

    public function store (Request $request)
    {
        $request->validate([
            // 'number' => ['nullable', 'unique:loadings'],
            'date' => ['required', 'date'],
            'reference_number' => ['nullable'],
            'reference_via' => ['required'],
            'reference_date' => ['required'],
            'reference_batch' => ['required',
                \Illuminate\Validation\Rule::unique('loadings')->where(function ($query) use($request) {
                    return $query->where('reference_via', $request->reference_via)
                                 ->where('reference_date', $request->reference_date)
                                 ->where('reference_batch', $request->reference_batch);
                })->ignore($request->id)
            ],
            'loading_items' => ['required', 'array'],
            'loading_items.*.serial' => ['required', 'unique:loading_items'],
            // 'loading_items.*.item.id' => ['required']
        ]);

        DB::beginTransaction();

        $loading = Loading::create([
            // 'number' => $request->number,
            'date' => $request->date,
            'reference_number' => $request->reference_number,
            'reference_via' => $request->reference_via,
            'reference_date' => $request->reference_date,
            'reference_batch' => $request->reference_batch

        ]);

        foreach ($request->loading_items as $key => $row) {

            if ($serial = Item::serial($row['serial']))
            {
                $detail = $loading->loading_items()->create([
                    'serial' => $row['serial'],
                    'item_id' => $serial->item->id,
                    'notes' => $row['notes'],
                ]);

                $serial->delete();
            }
            else $request->validate(["loading_items.$key.serial" => "not_in:". $row['serial']]);
        }

        DB::commit();

        return \response()->json($loading, 200);
    }

    public function show ($id)
    {
        $loading = Loading::with(['loading_items.item'])->findOrFail($id);

        // return response()->json($loading);
        return new LoadingResource($loading);
    }

    public function destroy ($id)
    {
        DB::beginTransaction();

        $loading = Loading::findOrFail($id);

        foreach ($loading->loading_items as $detail) {
            if (!$detail->item_serial) abort(501, "DETAIL SERIAL [#$detail->id] UNDEFINED.");
            $detail->item_serial->restore();
        }

        $loading->forceDelete();

        DB::commit();
        return response()->json(['success' => true]);
    }
}
