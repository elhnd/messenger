<?php

namespace App\MessageHandler;

use App\Entity\ImagePost;
use App\Message\AddPonkaToImage;
use App\Photo\PhotoFileManager;
use App\Photo\PhotoPonkaficator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddPonkaToImageHandler implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    
    public function __construct(
        private PhotoPonkaficator $ponkaficator,
        private PhotoFileManager $photoManager,
        private EntityManagerInterface $entityManager
    ) {}

    public function __invoke(AddPonkaToImage $addPonkaToImage)
    {
        $imagePostId = $addPonkaToImage->getImagePostId();
        $imagePost = $this->entityManager->getRepository(ImagePost::class)->find($imagePostId);

        if (!$imagePost) {
            if ($this->logger) {
                $this->logger->error(sprintf('ImagePost with id %d not found', $imagePostId));
            }
            return;
        }

        $updatedContents = $this->ponkaficator->ponkafy(
            $this->photoManager->read($imagePost->getFilename())
        );
        $this->photoManager->update($imagePost->getFilename(), $updatedContents);
        $imagePost->markAsPonkaAdded();
        $this->entityManager->flush();
    }
}
