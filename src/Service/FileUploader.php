<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /** @var string */
    private $targetDirectory;

    /**
     * @var string
     */
    private $blogDirectory;

    /**
     * @param string $targetDirectory
     * @param string $blogDirectory
     */
    public function __construct(string $targetDirectory, string $blogDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $this->blogDirectory   = $blogDirectory;
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * @return string
     */
    public function getBlogDirectory(): string
    {
        return $this->blogDirectory;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move($this->targetDirectory, $fileName);

        return $fileName;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function uploadBlog(UploadedFile $file): string
    {
        $filename = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move($this->blogDirectory, $filename);

        return $filename;
    }
}
