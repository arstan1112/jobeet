<?php

namespace App\Service;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $name
     *
     * @return Categories
     */
    public function create(string $name): Categories
    {
        $category = new Categories();
        $category->setName($name);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }
}
