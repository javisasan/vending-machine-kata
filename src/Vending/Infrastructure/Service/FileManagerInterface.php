<?php

namespace App\Vending\Infrastructure\Service;

interface FileManagerInterface
{
    public function getFileContent(string $filePath): string;
    public function saveFileContent(string $filePath, string $content): void;
    public function checkFileExists(string $filePath): void;
}
