<?php


namespace App\API;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @JMS\ExclusionPolicy("all")
 */
class JobUploadApi
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    public $filename;

    /**
     * @var string
     * @Assert\NotBlank()
     * @JMS\Expose()
     * @JMS\Type("string")
     *
     * @JMS\Accessor(setter="setData");
     */
    private $data;

    /**
     * @var string
     */
    public $decodedData;


    public function setData(?string $data)
    {
//        $this->data = $data;
        $this->decodedData = base64_decode($data);
    }
//    public function setDecodedData(?string $decodedData)
//    {
//        $this->decodedData = base64_decode($data);
//        $this->decodedData = $decodedData;
//    }

    public function getDecodedData(): ?string
    {
        return $this->decodedData;
    }

}