<?php

namespace App\Listeners;

use App\Events\BetCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Client;

class EmitBetCreatedToSocket implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BetCreated $event)
    {
        $client = new Client();
        $bet = $event->bet;

        $client->post('http://localhost:4000/broadcast', [
            'json' => [
                'event' => 'BetCreated',
                'data' => [
                    'bet' => $bet
                ]
            ]
        ]);
    }
}
