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

class YearbookInventoryController extends Controller
{
    public function index()
    {
        return view('yearbook.inventory.listbook');
    }

    public function show()
    {
        $data = Yearbooks::all();

        return response()->json(['data' => $data]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'school_year' => 'required',
                'edition_title' => 'required',
            ]);

            $schlyrName = $request->input('school_year');
            $editionName = $request->input('edition_title');

            $existingYrbokAY = Yearbooks::where('school_year', $schlyrName)
                    ->where('edition_title', $editionName)
                    ->first();

            if ($existingYrbokAY) {
                return response()->json(['error' => true, 'message' => 'Yearbook A.Y. already exists'], 404);
            }

            $cleanCost = str_replace(',', '', $request->input('unit_cost'));

            try {
                Yearbooks::create([
                    'school_year' => $request->input('school_year'),
                    'edition_title' => $request->input('edition_title'),
                    'total_ordered' => $request->input('total_ordered'),
                    'total_received' => $request->input('total_received'),
                    'unit_cost' => (float) $cleanCost,
                    'campus' => $request->input('campus'),
                ]);

                return response()->json(['success' => true, 'message' => 'Yearbook A.Y. stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Yearbook A.Y.'], 404);
            }
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'school_year' => 'required',
            'edition_title' => 'required',
        ]);

        try {
            $schlyrName = $request->input('school_year');
            $editionName = $request->input('edition_title');

            $existingYrbokAY = Yearbooks::where('school_year', $schlyrName)
                    ->where('edition_title', $editionName)
                    ->where('id', '!=', $request->input('id'))
                    ->first();

            if ($existingYrbokAY) {
                return response()->json(['error'=> true, 'message' => 'Yearbook A.Y. already exists!']);
            }

            $cleanCost = str_replace(',', '', $request->input('unit_cost'));

            $yrbk = Yearbooks::findOrFail($request->input('id'));
            $yrbk->update([
                'school_year' => $request->input('school_year'),
                'edition_title' => $request->input('edition_title'),
                'total_ordered' => $request->input('total_ordered'),
                'total_received' => $request->input('total_received'),
                'unit_cost' => (float) $cleanCost,
                'campus' => $request->input('campus'),
            ]);

            return response()->json(['success' => true, 'message' => 'Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update Yearbook A.Y.!']);
        }
    }
}
