<?php

namespace App\Photo;

use Doctrine\ORM\EntityManagerInterface;
use Intervention\Image\ImageManager;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Finder\Finder;

class PhotoPonkaficator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ImageManager $imageManager,
        private readonly FilesystemOperator $photoFilesystem,
    ) {
    }

    public function ponkafy(string $imageContents): string
    {
        $targetPhoto = $this->imageManager->read($imageContents);

        $ponkaFilename = $this->getRandomPonkaFilename();
        $ponkaPhoto = $this->imageManager->read(file_get_contents($ponkaFilename));

        $targetWidth = (int) ($targetPhoto->width() * .3);
        $targetHeight = (int) ($targetPhoto->height() * .4);

        $ponkaPhoto->scaleDown(width: $targetWidth, height: $targetHeight);

        $targetPhoto->place($ponkaPhoto, 'bottom-right');

        // for dramatic effect, make this *really* slow
        sleep(2);

        return (string) $targetPhoto->encodeByMediaType();
    }

    private function getRandomPonkaFilename(): string
    {
        $finder = new Finder();
        $finder->in(__DIR__.'/../../assets/ponka')
            ->files();

        // array keys are the absolute file paths
        $ponkaFiles = iterator_to_array($finder->getIterator());

        return array_rand($ponkaFiles);
    }
}
