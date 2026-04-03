<?php

namespace App\Message\Command;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('external_messages')]
class LogEmoji
{
    public function __construct(
        private int $imojiIndex
    ) {}

    public function getImojiIndex(): int
    {
        return $this->imojiIndex;
    }
}
