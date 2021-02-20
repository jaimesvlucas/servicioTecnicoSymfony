<?php

namespace App\Repository;

use App\Entity\LineasIncidencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LineasIncidencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineasIncidencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineasIncidencia[]    findAll()
 * @method LineasIncidencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineasIncidenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LineasIncidencia::class);
    }

     /**
     * @return LineasIncidencia[] Returns an array of LineasIncidencia objects
     */
    
    public function findByIdIncidencia(int $id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT li, i
            FROM App\Entity\LineasIncidencia li INNER JOIN li.incidencia i
            WHERE i.id = :id
            ORDER BY i.fecha_creacion ASC'
        )->setParameter('id', $id);

        // returns an array of Product objects
        return $query->getResult();
    }
    

    /*
    public function findOneBySomeField($value): ?LineasIncidencia
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
