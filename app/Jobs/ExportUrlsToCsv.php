<?php

namespace App\Jobs;

use App\Models\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class ExportUrlsToCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filter;
    protected $userId;

    public function __construct($filter, $userId)
    {
        $this->filter = $filter;
        $this->userId = $userId;
    }

    public function handle()
    {
        // Build the query based on the selected filter
        $query = Url::with('user')
            ->where('client_id', $this->userId);

        switch ($this->filter) {
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

        $urls = $query->get();

        // Create a CSV file
        $csv = Writer::createFromPath(storage_path('app/exports/urls.csv'), 'w');
        $csv->insertOne(['Short URL', 'Long URL', 'Hits', 'Created By', 'Created On']);

        foreach ($urls as $url) {
            $csv->insertOne([
                $url->short_url,
                $url->long_url,
                $url->hits,
                $url->user->name ?? 'NA',
                $url->created_at->format('d M \'y'),
            ]);
        }

        
        Storage::disk('public')->put('exports/urls.csv', file_get_contents(storage_path('app/exports/urls.csv')));
    }
}
