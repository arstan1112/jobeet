<?php

namespace App\Service;

use Exception;
use App\Entity\Jobs;
use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
//use Hshn\Base64EncodedFile\Form\Type\Base64EncodedFileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class JobSaveService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FileUploader $fileUploader
     */
    private $fileUploader;

    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * @param EntityManagerInterface $em
     * @param FileUploader           $fileUploader
     * @param string                 $targetDirectory
     */
    public function __construct(EntityManagerInterface $em, FileUploader $fileUploader, string $targetDirectory)
    {
        $this->em = $em;
        $this->fileUploader = $fileUploader;
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param Jobs $uploadApi
     *
     * @throws Exception
     */
    public function saveJob(Jobs $uploadApi)
    {
//        $id = $uploadApi->getCategoryId();
//        $category = $this
//            ->em
//            ->getRepository(Categories::class)
//            ->find(
//                $uploadApi->getCategoryId()
//            );
//        $category = $uploadApi->getCategories();
//        if ($category == null) {
//            throw new Exception('Category not found.');
//        }
//        $uploadApi->setCategories($category);

        $fileEncoded = $uploadApi->getLogodata();
        $file = new UploadedBase64EncodedFile(new Base64EncodedFile($fileEncoded));

//        $tmpPath = sys_get_temp_dir().'/sf_upload'.uniqid();
//        file_put_contents($tmpPath, $uploadApi->getDecodedLogodata());
//        $uploadedFile = new FileObject($tmpPath);
//        $filePathname = $uploadedFile->getPathname() . '.' . $uploadedFile->guessExtension();
//        $fileName     = $uploadedFile->getFilename() . '.' . $uploadedFile->guessExtension();
//        $filePathname = $file->getPathname() . '.' . $file->guessExtension();
//        $fileName     = $file->getFilename() . '.' . $file->guessExtension();

//        $uploadedFile->move($this->targetDirectory, $filePathname);
//        $file->move($this->targetDirectory, $filePathname);
//        $uploadApi->setLogo($fileName);

//        if ($file instanceof  UploadedFile) {
//        if ($file instanceof FileObject) {
            $fileName = $this->fileUploader->upload($file);
            $uploadApi->setLogo($fileName);
//            dump('upload success');
//            dump($fileName);
//        }
//        die();
        $this->em->persist($uploadApi);
        $this->em->flush();
    }
}
