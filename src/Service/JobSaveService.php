<?php

namespace App\Service;

use Exception;
use App\Entity\Jobs;
use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
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
        $category = $this
            ->em
            ->getRepository(Categories::class)
            ->find(
                $uploadApi->getCategoryId()
            );


        if ($category == null) {
            throw new Exception('Category not found.');
        }

        $uploadApi->setCategories($category);

        $file = new Base64EncodedFile(base64_decode($uploadApi->getLogodata()));
        $file = new Base64EncodedFile(base64_encode($uploadApi->getDecodedlogodata()));

        dump($file);
        die();

//        $tmpPath = sys_get_temp_dir().'/sf_upload'.uniqid();

//        file_put_contents($tmpPath, $uploadApi->getDecodedLogodata());
//        $uploadedFile = new FileObject($tmpPath);
//        $filePathname = $uploadedFile->getPathname() . '.' . $uploadedFile->guessExtension();
//        $fileName     = $uploadedFile->getFilename() . '.' . $uploadedFile->guessExtension();

//        $uploadedFile->move($this->targetDirectory, $filePathname);
        dump('base64 success');
        if ($file instanceof UploadedFile) {
            $fileName = $this->fileUploader->upload($file);
            $uploadApi->setLogo($fileName);
            dump('upload success');
            dump($fileName);
        }
        die();
        $this->em->persist($uploadApi);
        $this->em->flush();
    }
}
