<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    /**
     * Health check endpoint for monitoring.
     */
    public function index(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
            'storage' => $this->checkStorage(),
        ];

        $healthy = ! in_array('error', array_column($checks, 'status'));

        return response()->json([
            'status' => $healthy ? 'healthy' : 'degraded',
            'timestamp' => now()->toISOString(),
            'checks' => $checks,
        ], $healthy ? 200 : 503);
    }

    /**
     * Check database connectivity.
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return [
                'status' => 'ok',
                'message' => 'Database connection successful',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Check cache connectivity.
     */
    private function checkCache(): array
    {
        try {
            $key = 'health_check_'.time();
            Cache::put($key, 'ok', 10);
            $value = Cache::get($key);
            Cache::forget($key);

            return [
                'status' => $value === 'ok' ? 'ok' : 'error',
                'message' => $value === 'ok' ? 'Cache working' : 'Cache read/write failed',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cache check failed: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Check queue status.
     */
    private function checkQueue(): array
    {
        try {
            $size = Queue::size();
            $status = $size < 1000 ? 'ok' : ($size < 5000 ? 'warning' : 'error');

            return [
                'status' => $status,
                'message' => "Queue size: {$size}",
                'queue_size' => $size,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Queue check failed: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Check storage accessibility.
     */
    private function checkStorage(): array
    {
        try {
            $testFile = 'health_check_'.time().'.txt';
            Storage::disk('local')->put($testFile, 'health check');
            Storage::disk('local')->delete($testFile);

            return [
                'status' => 'ok',
                'message' => 'Storage accessible',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Storage check failed: '.$e->getMessage(),
            ];
        }
    }
}
