<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


use Storage;
use Carbon\Carbon;
use App\Models\ScholarshipDB\Scholar;

class ScholarshipController extends Controller
{
    public function index()
    {
        return view('scholar.index');
    }

    public function scholarAdd()
    {
        return view('scholar.add.addScholar');
    }

    public function scholarCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'scholar_name' => 'required',
                'scholar_sponsor' => 'required',
                'scholar_category' => 'required',
                'fund_source' => 'required',
            ]);

            try {
                Scholar::create([
                    'scholar_name' => $request->input('scholar_name'),
                    'scholar_sponsor' => $request->input('scholar_sponsor'),
                    'scholar_category' => $request->input('scholar_category'),
                    'fund_source' => $request->input('fund_source'),
                ]);

                return redirect()->route('scholarAdd')->with('success', 'Scholarship Name stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route('scholarAdd')->with('fail', 'Failed to store Scholarship!');
            }
        }
    }

    public function chedscholarRead()
    {
        return view('scholar.list.list_scholar');
    }

    public function chedscholarSearch(Request $request)
    {
        $category = $request->query('category');

        $category = is_array($category) ? $category : [$category];

        $data = Scholar::select('scholarship.*')
                        ->where('scholarship.category', $category)
                        ->where('status', '=', 1)
                        ->get();
        $totalSearchResults = count($data);

        return view('scholar.list.listsearch_scholar', compact('data', 'totalSearchResults'));
    }
}
