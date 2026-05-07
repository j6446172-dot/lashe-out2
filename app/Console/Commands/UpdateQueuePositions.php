<?php

namespace App\Console\Commands;

use App\Models\Queue;
use Illuminate\Console\Command;

class UpdateQueuePositions extends Command
{
    protected $signature = 'queue:update-positions';
    protected $description = 'تحديث أرقام الطابور بعد إلغاء الحجوزات';

    public function handle()
    {
        $queues = Queue::where('status', 'waiting')
            ->orderBy('position', 'asc')
            ->get();
        
        $position = 1;
        foreach ($queues as $queue) {
            $queue->update(['position' => $position]);
            $position++;
        }
        
        $this->info('تم تحديث أرقام الطابور بنجاح');
    }
}