<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Download;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index()
    {
       
         $downloads = Download::orderBy('created_at', 'desc')->paginate(6);
         return view('downloads.index', compact('downloads'));
    }

    public function showUploadPage()
    {
        return view('downloads.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,mp4,avi,mov,doc,docx,txt,xls,xlsx|max:20480'
        ]);

        $filePath = $request->file('file')->store('uploads', 'public');

        Download::create([
            'title' => $request->title,
            'category' => $request->category,
            'file_path' => $filePath,
        ]);

        return redirect()->route('downloads.index')->with('success', 'File uploaded successfully!');
    }

    public function download($file)
    {
        return response()->download(storage_path("app/public/uploads/{$file}"));
    }

   public function destroy($id)
{
    // Ensure user is authenticated
    if (!auth()->check()) {
        return redirect()->route('downloads.index')->with('error', 'You must be logged in to delete documents.');
    }

    $download = Download::findOrFail($id);

    // Delete file from storage
    Storage::disk('public')->delete($download->file_path);

    // Delete from database
    $download->delete();

    return redirect()->route('downloads.index')->with('success', 'Document deleted successfully!');
}

}
