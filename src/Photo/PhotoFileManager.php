<?php

namespace App\Photo;

use App\Entity\ImagePost;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Visibility;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoFileManager
{
    public function __construct(
        private readonly FilesystemOperator $photoFilesystem,
        private readonly string $publicAssetBaseUrl,
    ) {
    }

    public function uploadImage(File $file): string
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }

        $newFilename = pathinfo($originalFilename, PATHINFO_FILENAME).'-'.uniqid().'.'.$file->guessExtension();
        $stream = fopen($file->getPathname(), 'r');
        $this->photoFilesystem->writeStream(
            $newFilename,
            $stream,
            [
                'visibility' => Visibility::PUBLIC
            ]
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $newFilename;
    }

    public function deleteImage(string $filename): void
    {
        // make it a bit slow
        sleep(3);

        $this->photoFilesystem->delete($filename);
    }

    public function getPublicPath(ImagePost $imagePost): string
    {
        return $this->publicAssetBaseUrl.'/'.$imagePost->getFilename();
    }

    public function read(string $filename): string
    {
        return $this->photoFilesystem->read($filename);
    }

    public function update(string $filename, string $updatedContents): void
    {
        $this->photoFilesystem->write($filename, $updatedContents);
    }
}
