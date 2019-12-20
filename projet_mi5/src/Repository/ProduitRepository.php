<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }


    public function findProduitsByName(string $search) {
       return $this->createQueryBuilder('prod')
           ->andWhere('prod.libelle LIKE :search')
           ->setParameter('search','%'.$search.'%')
           ->orWhere('prod.texte LIKE :search')
           ->setParameter('search','%'.$search.'%')
           ->getQuery()
           ->getResult();
    }
}
