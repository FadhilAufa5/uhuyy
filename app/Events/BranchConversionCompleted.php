<?php

namespace App\Events;

use App\Models\Branch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BranchConversionCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Branch $branch;

    /**
     * Create a new event instance.
     */
    public function __construct(Branch $branch)
    {
        $this->branch = $branch;
    }
}
