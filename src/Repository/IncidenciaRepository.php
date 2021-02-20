<?php

namespace App\Repository;

use App\Entity\Incidencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Incidencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Incidencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Incidencia[]    findAll()
 * @method Incidencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncidenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Incidencia::class);
    }

    /**
     * @return Incidencia[] Returns an array of Incidencia objects
     */
    
    public function findByIdCliente(int $id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT i, c
            FROM App\Entity\Incidencia i INNER JOIN i.cliente c
            WHERE c.id = :id
            ORDER BY i.fecha_creacion ASC'
        )->setParameter('id', $id);

        // returns an array of Product objects
        return $query->getResult();
    }
    
    public function findByIdUsuario(int $id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT i, c
            FROM App\Entity\Incidencia i INNER JOIN i.usuario c
            WHERE c.id = :id
            ORDER BY i.fecha_creacion ASC'
        )->setParameter('id', $id);

        // returns an array of Product objects
        return $query->getResult();
    }
    
    public function removeLineasIncidencias(Incidencia $id){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
           'DELETE
            FROM App\Entity\LineasIncidencia li 
            WHERE li.incidencia = :incidencia'
        )->setParameter('incidencia', $id);

        // returns an array of Product objects
        return $query->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Incidencia
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
