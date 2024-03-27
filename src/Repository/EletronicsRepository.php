<?php

namespace App\Repository;

use App\Entity\Eletronics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eletronics>
 *
 * @method Eletronics|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eletronics|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eletronics[]    findAll()
 * @method Eletronics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EletronicsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eletronics::class);
    }

    public function add(Eletronics $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Eletronics $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filterTable(
        string $nome = null,
        string $preco = null,
        int $offset = 0,
        int $pageSize = 10): array
    {
        $qb = $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($pageSize);

        if ($nome) {
            $qb->andWhere('f.nome LIKE :nome')
                ->setParameter('nome', '%' . $nome . '%');
        }

        if ($preco) {
            $qb->andWhere('f.preco = :preco')
                ->setParameter('preco', $preco);
        }

        return $qb->getQuery()->getResult();
    }

    public function filterCount(string $nome = null, string $preco = null): int
    {
        $qb = $this->createQueryBuilder('f');

        if ($nome) {
            $qb->andWhere('f.nome LIKE :nome')
                ->setParameter('nome', '%' . $nome . '%');
        }

        if ($preco) {
            $qb->andWhere('f.preco = :preco')
                ->setParameter('preco', $preco);
        }

        return $qb->select('COUNT(f.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Eletronics[] Returns an array of Eletronics objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Eletronics
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
