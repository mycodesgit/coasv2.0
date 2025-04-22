<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\RequestdocsDB\DocsList;


class DocumentRequestController extends Controller
{
    public function docsRead()
    {
        return view('documentrequest.docsconfig.listdocs');
    }

    public function getdocsRead() 
    {
        $data = DocsList::orderBy('id', 'ASC')->get();

        return response()->json(['data' => $data]);
    }

    public function docsCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'docname' => 'required',
            ]);

            $docName = $request->input('docname'); 
            $existingDocs = DocsList::where('docname', $docName)->first();

            if ($existingDocs) {
                return response()->json(['error' => true, 'message' => 'Document already exists'], 404);
            }

            try {
                DocsList::create([
                    'docname' => $request->input('docname'),
                ]);

                return response()->json(['success' => true, 'message' => 'Document stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Document'], 404);
            }
        }
    }

    public function docsUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'docname' => 'required',
        ]);

        try {
            $docName = $request->input('docname');
            $existingDocs = DocsList::where('docname', $docName)->where('id', '!=', $request->input('id'))->first();

            if ($existingDocs) {
                return response()->json(['error' => true, 'message' => 'Document already exists'], 404);
            }

            $docs = DocsList::findOrFail($request->input('id'));
            $docs->update([
                'docname' => $docName,
        ]);
            return response()->json(['success' => true, 'message' => 'Document update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Document'], 404);
        }
    }
}
