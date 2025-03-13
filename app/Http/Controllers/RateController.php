<?php

namespace App\Http\Controllers;

use App\Imports\RatesImport;
use App\Models\Country;
use App\Models\Rate;
use App\Models\RatecardFile;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RateController extends Controller
{
    /**
     * Upload ratecard
     *
     * Function to handle excel file upload
     *
     **/
    public function upload(Request $request)
    {
        try {
            $country = Country::findOrFail($request->country_id);

            $file = $request->file('file');
            $type = $request->type;

            DB::beginTransaction();

            Excel::import(new RatesImport($country, $type), $file);

            $now = Carbon::now();

            $filename = $file->getClientOriginalName();
            $filePath = $file->storeAs('/ratecards/'.$country->code, $now->timestamp. '_'. $filename);

            RatecardFile::create([
                'country_id' => $country->id,
                'type' => $type,
                'name' => $filename,
                'path' => $filePath
            ]);

            DB::commit();

            return redirect()->back()->with('success', $this->successMessage('save', Rate::NAME));
            
        } catch (Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Download uploaded ratecard
     *
     * Enable user to download uploaded ratecard for reference
     *
     **/
    public function download(Request $request)
    {
        try {

            $file = RatecardFile::findOrFail($request->file_id);
            
            if (Storage::exists($file->path)) {
                return Storage::download($file->path);
            } else {
                return redirect()->back()->with('error', 'File does not exist.');
            }

        } catch (Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
