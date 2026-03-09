<?php
namespace App\Jobs;

use App\Imports\ProductImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1800;
    public $tries   = 1;

    protected $filePath;
    protected $cacheKey;

    public function __construct(string $filePath, string $cacheKey)
    {
        $this->filePath = $filePath;
        $this->cacheKey = $cacheKey;
    }

    public function handle()
    {
        try {
            Cache::put($this->cacheKey, ['status' => 'processing'], now()->addMinutes(60));

            $productImport = new ProductImport();
            Excel::import($productImport, $this->filePath, null, \Maatwebsite\Excel\Excel::CSV);

            Cache::put($this->cacheKey, [
                'status'   => 'completed',
                'products' => $productImport->getImportedProducts(),
                'count'    => count($productImport->getImportedProducts()),
            ], now()->addMinutes(60));

        } catch (\Exception $e) {
            Cache::put($this->cacheKey, [
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], now()->addMinutes(60));
        } finally {
            if (file_exists($this->filePath)) {
                unlink($this->filePath);
            }
        }
    }
}