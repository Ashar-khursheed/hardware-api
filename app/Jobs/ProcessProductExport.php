<?php
// app/Jobs/ProcessProductExport.php

namespace App\Jobs;

use App\Exports\ProductsExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class ProcessProductExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 minutes
    public $tries = 1;

    protected $filters;
    protected $cacheKey;

    public function __construct(array $filters, string $cacheKey)
    {
        $this->filters = $filters;
        $this->cacheKey = $cacheKey;
    }

    public function handle()
    {
        $filename = 'exports/products_' . now()->format('Ymd_His') . '.csv';

        Excel::store(new ProductsExport($this->filters), $filename, 'public');

        $url = \Storage::disk('public')->url($filename);

        // ✅ Store URL in cache for 30 minutes — frontend polls for it
        Cache::put($this->cacheKey, [
            'status' => 'done',
            'url'    => $url
        ], now()->addMinutes(30));
    }

    public function failed(\Throwable $e)
    {
        Cache::put($this->cacheKey, [
            'status' => 'failed',
            'error'  => $e->getMessage()
        ], now()->addMinutes(10));
    }
}