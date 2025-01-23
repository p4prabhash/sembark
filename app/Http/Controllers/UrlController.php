<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ExportUrlsToCsv;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;


class UrlController extends Controller
{
    public function create()
    {
        return view('urls.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'long_url' => 'required|url',
        ]);

        $shortUrl = $this->generateUniqueShortUrl();

        $url = Url::create([
            'long_url' => $request->long_url,
            'short_url' => $shortUrl,
            'client_id' => auth()->id(),
            'created_by' => auth()->id(),
            'generated_via' => 'Manual',
        ]);

        return redirect()
        ->route('urls.index')
        ->with('success', 'Short URL generated: successfully!');

    }

    public function index(Request $request)
    {
        $query = Url::with('user');
        
        
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'this_month':
                    $query->whereMonth('created_at', '=', now()->month);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', '=', now()->subMonth()->month);
                    break;
                case 'last_week':
                    $query->whereBetween('created_at', [now()->subWeek(), now()]);
                    break;
                case 'today':
                    $query->whereDate('created_at', '=', now()->toDateString());
                    break;
            }
        }
    
        $urls = $query->paginate(10);
    
        return view('urls.index', compact('urls'));
    }

    public function downloadCsv(Request $request)
    {
        // Filter logic
        $query = Url::with('user');
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'this_month':
                    $query->whereMonth('created_at', '=', now()->month);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', '=', now()->subMonth()->month);
                    break;
                case 'last_week':
                    $query->whereBetween('created_at', [now()->subWeek(), now()]);
                    break;
                case 'today':
                    $query->whereDate('created_at', '=', now()->toDateString());
                    break;
            }
        }

        $urls = $query->get();
        $csvData = [['Short URL', 'Long URL', 'Hits', 'Created By', 'Created On']];

        foreach ($urls as $url) {
            $csvData[] = [
                $url->short_url,
                $url->long_url,
                $url->hits,
                $url->user->name ?? 'NA',
                $url->created_at->format('d M \'y'),
            ];
        }

        // Generate CSV data as a stream
        $csvFileName = 'urls_' . Carbon::now()->format('Y_m_d_H_i_s') . '.csv';
        $csvPath = public_path('downloads/' . $csvFileName); // Temporary file in public directory

        // Create the CSV file in the 'downloads' directory
        $file = fopen($csvPath, 'w');
        foreach ($csvData as $line) {
            fputcsv($file, $line);
        }
        fclose($file);

        // Return the file as a response for download
        return response()->download($csvPath)->deleteFileAfterSend(true); // Automatically delete after download
    }
    
    
    // private function generateUniqueShortUrl()
    // {
    //     do {
    //         $shortUrl = substr(md5(uniqid(rand(), true)), 0, 8);
    //     } while (Url::where('short_url', $shortUrl)->exists());

    //     return $shortUrl;
    // }
    private function generateUniqueShortUrl()
{
    $baseDomain = 'https://www.example.com/'; 

    do {
        $shortIdentifier = substr(md5(uniqid(rand(), true)), 0, 8);
        $shortUrl = $baseDomain . $shortIdentifier;
    } while (Url::where('short_url', $shortUrl)->exists());

    return $shortUrl;
}

}
