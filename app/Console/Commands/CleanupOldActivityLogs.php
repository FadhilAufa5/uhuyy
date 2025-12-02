<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class CleanupOldActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:cleanup 
                            {--days=90 : Number of days to keep logs} 
                            {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old activity logs to free up database space and improve performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $force = $this->option('force');
        
        $this->info("🔍 Checking activity logs older than {$days} days...");
        
        // Count logs to be deleted
        $oldLogsCount = ActivityLog::where('created_at', '<', now()->subDays($days))->count();
        
        if ($oldLogsCount === 0) {
            $this->info('✅ No old activity logs found. Database is clean!');
            return 0;
        }
        
        $this->warn("Found {$oldLogsCount} log(s) older than {$days} days.");
        
        // Confirm deletion unless force flag is used
        if (!$force) {
            if (!$this->confirm('Do you want to delete these logs?', true)) {
                $this->info('❌ Cleanup cancelled.');
                return 1;
            }
        }
        
        $this->info('🗑️  Deleting old logs...');
        
        try {
            // Delete in chunks to avoid memory issues
            $deleted = 0;
            $bar = $this->output->createProgressBar($oldLogsCount);
            $bar->start();
            
            ActivityLog::where('created_at', '<', now()->subDays($days))
                ->chunkById(1000, function ($logs) use (&$deleted, $bar) {
                    $chunkSize = $logs->count();
                    
                    // Delete chunk
                    DB::table('activity_logs')
                        ->whereIn('id', $logs->pluck('id'))
                        ->delete();
                    
                    $deleted += $chunkSize;
                    $bar->advance($chunkSize);
                });
            
            $bar->finish();
            $this->newLine(2);
            
            // Optimize table after deletion
            $this->info('🔧 Optimizing database table...');
            DB::statement('OPTIMIZE TABLE activity_logs');
            
            $this->info("✅ Successfully deleted {$deleted} old activity log(s)!");
            $this->info('💾 Database space freed and table optimized.');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error during cleanup: ' . $e->getMessage());
            return 1;
        }
    }
}
