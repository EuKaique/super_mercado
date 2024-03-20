<?php

namespace App\Repository;

use App\Entity\Food;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Food>
 *
 * @method Food|null find($id, $lockMode = null, $lockVersion = null)
 * @method Food|null findOneBy(array $criteria, array $orderBy = null)
 * @method Food[]    findAll()
 * @method Food[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Food::class);
    }

    public function add(Food $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Food $entity, bool $flush = false): void
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
//     * @return Food[] Returns an array of Food objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Food
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
