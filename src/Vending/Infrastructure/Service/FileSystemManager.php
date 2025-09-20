<?php

namespace App\Vending\Infrastructure\Service;

class FileSystemManager implements FileManagerInterface
{
    public function getFileContent(string $filePath): string
    {
        $this->checkFileExists($filePath);

        return file_get_contents($filePath);
    }

    public function saveFileContent(string $filePath, string $content): void
    {
        $this->checkFileExists($filePath);

        file_put_contents($filePath, $content);
    }

    public function checkFileExists(string $filePath): void
    {
        $pathParts = pathinfo($filePath);

        if (!file_exists($pathParts['dirname'])) {
            mkdir($pathParts['dirname']);
        }

        if (!file_exists($filePath)) {
            touch($filePath);
        }
    }
}
