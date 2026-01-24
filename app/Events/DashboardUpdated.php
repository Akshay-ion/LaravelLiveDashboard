<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $categoryCount;

    public function __construct($categoryCount)
    {
        $this->categoryCount = $categoryCount;
    }

    public function broadcastOn()
    {
        return new Channel('dashboard');
    }

    public function broadcastAs()
    {
        return 'DashboardUpdated';
    }
}
