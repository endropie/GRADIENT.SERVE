<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Receive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiveController extends Controller
{
    public function index (Request $request)
    {
        switch ($request->get('mode')) {
            case 'all':
                $items = Receive::latest()->all();
                break;

            default:
            $items = Receive::latest()->paginate();
                break;
        }

        return response()->json($items);
    }

    public function store (Request $request)
    {
        $request->validate([
            // 'number' => ['nullable', 'unique:receives'],
            'date' => ['required', 'date'],
            'reference_number' => ['nullable'],
            'reference_batch' => ['nullable', 'required_without:referece_number',
                \Illuminate\Validation\Rule::unique('receives')->where(function ($query) use($request) {
                    return $query->where('reference_number', $request->reference_number)
                                 ->where('reference_batch', $request->reference_batch);
                })->ignore($request->id)
            ],
            'receive_items' => ['required', 'array'],
            'receive_items.*.serial' => ['required', 'unique:receive_items'],
            'receive_items.*.item.id' => ['required']
        ]);

        DB::beginTransaction();

        $receive = Receive::create([
            // 'number' => $request->number,
            'date' => $request->date,
            'reference_number' => $request->reference_number,
            'reference_batch' => $request->reference_batch

        ]);

        foreach ($request->receive_items as $key => $row) {

            $item = Item::findOrFail($row['item']['id']);
            if (!$item) $request->validate(["receive_items.$key.item.id" => "not_in:". $row['item']['id']]);

            $item->item_serials()->create(['serial' => $row['serial']]);

            $receive->receive_items()->create([
                'serial' => $row['serial'],
                'item_id' => $row['item']['id'],
                'notes' => $row['notes'],
            ]);
        }

        DB::commit();

        return response()->json($receive, 200);
    }

    public function show ($id)
    {
        $receive = Receive::with(['receive_items.item'])->findOrFail($id);

        return response()->json($receive);
    }

    public function destroy ($id)
    {
        DB::beginTransaction();

        $receive = Receive::findOrFail($id);

        foreach ($receive->receive_items as $detail) {
            $detail->item_serial->delete();
        }

        $receive->forceDelete();

        DB::commit();
        return response()->json(['success' => true]);
    }
}
