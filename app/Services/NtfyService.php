<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NtfyService
{
    protected string $topic;

    public function __construct()
    {
        $this->topic = config('services.ntfy.topic');
    }

    public function send(string $title, string $message, string $priority = 'default'): void
    {
        if (!$this->topic) {
            return;
        }

        Http::withHeaders([
            'Title'    => $title,
            'Priority' => $priority,
            'Content-Type' => 'text/plain; charset=utf-8',
        ])->withBody($message, 'text/plain')
          ->post("https://ntfy.sh/{$this->topic}");
    }
}
