<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use Storage;
use Carbon\Carbon;

use App\Models\YearBookDB\Yearbooks;
use App\Models\YearBookDB\YearbookShipment;

class YearbookShipmentController extends Controller
{
    public function index()
    {
        $yearbooks = Yearbooks::all();
        return view('yearbook.inventory.listbookshipment', compact('yearbooks'));
    }

    public function show()
    {
        // Load shipments along with parent yearbook batch info
        $data = YearbookShipment::with('yearbook')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $data]);
    }

    // STEP 1: Supplier Dispatches / Releases Shipment
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'yearbook_id' => 'required',
                'supplier_name' => 'required',
                'quantity_sent' => 'required|numeric|min:1',
            ]);

            try {
                YearbookShipment::create([
                    'yearbook_id' => $request->input('yearbook_id'),
                    'supplier_name' => $request->input('supplier_name'),
                    'tracking_number' => $request->input('tracking_number'),
                    'quantity_sent' => $request->input('quantity_sent'),
                    'quantity_received' => 0,
                    'status' => 'released_by_supplier',
                    'released_at' => Carbon::now(),
                    'notes' => $request->input('notes'),
                ]);

                return response()->json(['success' => true, 'message' => 'Shipment created and set to in-transit!'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to log shipment release.'], 500);
            }
        }
    }

    // STEP 2: Office Receives Shipment (Triggers Inventory Increment)
    public function receive(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'quantity_received' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $shipment = YearbookShipment::findOrFail($request->input('id'));

                // Prevent double processing
                if ($shipment->status === 'received_by_office') {
                    throw new \Exception('Shipment has already been received.');
                }

                $qtyReceived = (int) $request->input('quantity_received');
                $status = ($qtyReceived < $shipment->quantity_sent) ? 'disputed' : 'received_by_office';

                // 1. Update Shipment record
                $shipment->update([
                    'quantity_received' => $qtyReceived,
                    'status' => $status,
                    'received_at' => Carbon::now(),
                    'notes' => $request->input('notes'),
                ]);

                // 2. AUTOMATIC CONNECTION: Increment the parent Yearbook's total_received inventory
                $yrbook = Yearbooks::findOrFail($shipment->yearbook_id);
                $yrbook->increment('total_received', $qtyReceived);
            });

            return response()->json(['success' => true, 'message' => 'Stock received and inventory total updated!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage() ?: 'Failed to process shipment reception.'], 500);
        }
    }
}
