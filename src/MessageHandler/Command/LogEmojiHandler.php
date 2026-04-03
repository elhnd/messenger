<?php

namespace App\MessageHandler\Command;

use App\Message\Command\LogEmoji;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus' /*,method: '__invoke', priority: 10, fromTransport: 'async'*/)]
class LogEmojiHandler
{
    private static array $emojis = [
        '😎', '🙈', '🎉', '🚀', '🌟', '🍕',
    ];

    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function __invoke(LogEmoji $logEmoji)
    {
        $index = $logEmoji->getImojiIndex();

        $emoji = self::$emojis[$index] ?? self::$emojis[0];
        $this->logger->info('Important message with emoji: ' . $emoji);
    }
}