<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct($registry, Product::class);
    }

    public function setCreate(Product $item): object
    {
        $item->setCreateAtValue();
        $item->setUpdateAtValue();
        //$item->setActive(true);
        $this->manager->persist($item);
        $this->manager->flush();
        return $item;
    }

    public function setUpdate(Product $item): object
    {
        $item->setUpdateAtValue();
        $this->manager->flush();
        return $item;
    }

    public function setDelete(Product $item): void
    {
        $this->manager->remove($item);
        $this->manager->flush();
    }
}
