<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
final class SendPointsMessage
{

    public function __construct(
        public readonly string $points,
    ) {
    }

     public function getPoints(): int
     {
         return $this->points;
     }
}
