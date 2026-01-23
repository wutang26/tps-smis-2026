<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Imports\BulkImportStudents;
use Maatwebsite\Excel\Facades\Excel;
//use Excel;
use Illuminate\Http\Request;

class ImportExportStudentsController extends Controller
{
    // public function importExportView()
    // {
    //    return view('importexport');
    // }
    public function import(Request $request)
    {
      $validator=   Validator::make($request->all(),[
            'import_file' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                        $fail('Incorrect :attribute type choose.');
                    }
                }
            ],
        ]);
        if($validator->fails()){
            return back()->with('success',$validator->errors()->first());
        }
        Excel::import(new BulkImportStudents, filePath: $request->file('import_file'));
        return back()->with('success', 'Students Uploaded  successfully.');
    }
}
