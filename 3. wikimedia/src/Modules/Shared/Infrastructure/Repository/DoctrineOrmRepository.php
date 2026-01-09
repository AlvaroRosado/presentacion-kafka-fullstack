<?php

namespace App\Modules\Shared\Infrastructure\Repository;

use App\Modules\Shared\Domain\EntityNotFoundException;
use App\Modules\Shared\Domain\OrderBy;
use App\Modules\Shared\Domain\Paginator;
use App\Modules\Shared\Domain\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrineToolPaginator;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Contracts\Service\ResetInterface;

abstract class DoctrineOrmRepository extends EntityRepository implements Repository, ResetInterface
{
    protected QueryBuilder $queryBuilder;
    private ?int $page = null;
    private ?int $itemsPerPage = null;
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $classMetadata = $em->getClassMetadata($this->entityClass());
        parent::__construct($em, $classMetadata);
        $this->em = $em;
        $this->initializeQueryBuilder();
    }

    protected function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }

    public function getIterator(): \Iterator
    {
        if (null !== $paginator = $this->paginator()) {
            yield from $paginator;

            return;
        }

        yield from $this->queryBuilder->getQuery()->getResult();
    }

    public function asArray(): array
    {
        return iterator_to_array($this->getIterator());
    }

    public function flush(): void
    {
        $this->em->flush();
    }

    public function getOneOrNull(): ?object
    {
        return $this->queryBuilder->getQuery()->setMaxResults(1)->getOneOrNullResult();
    }

    public function getOneOrFail(): object
    {
        $result = $this->getOneOrNull();
        if (!$result) {
            throw EntityNotFoundException::forEntity($this->entityClass(), $this->getQueryDebugInfo());
        }

        return $result;
    }

    private function getQueryDebugInfo(): array
    {
        $parameters = [];
        $types = [];

        foreach ($this->queryBuilder->getParameters() as $parameter) {
            $name = $parameter->getName();
            $value = $parameter->getValue();

            if (is_object($value)) {
                $formattedValue = get_class($value) . (method_exists($value, '__toString') ? ': ' . $value : '');
            } elseif (is_array($value)) {
                $formattedValue = json_encode($value);
            } elseif (is_bool($value)) {
                $formattedValue = $value ? 'true' : 'false';
            } elseif ($value === null) {
                $formattedValue = 'null';
            } else {
                $formattedValue = (string) $value;
            }

            $parameters[$name] = $formattedValue;
            $types[$name] = $parameter->getType() ?? 'unknown';
        }

        return [
            'dql' => $this->queryBuilder->getDQL(),
            'parameters' => $parameters,
            'types' => $types,
        ];
    }

    public function paginator(): ?Paginator
    {
        if (null === $this->page || null === $this->itemsPerPage) {
            return null;
        }

        $doctrinePaginator = new DoctrinePaginator(new QueryAdapter($this->queryBuilder));
        $doctrinePaginator->changePage($this->page);
        $doctrinePaginator->changeMaxPerPage($this->itemsPerPage);

        return $doctrinePaginator;
    }

    public function withoutPagination(): static
    {
        $cloned = clone $this;
        $cloned->page = null;
        $cloned->itemsPerPage = null;

        return $cloned;
    }

    public function withPagination(int $page, int $itemsPerPage): static
    {
        $cloned = clone $this;
        $cloned->page = $page;
        $cloned->itemsPerPage = $itemsPerPage;

        return $cloned;
    }

    public function countPaginatorItems(): int
    {
        $paginator = $this->paginator() ?? new DoctrineToolPaginator(clone $this->queryBuilder);

        return $paginator->count();
    }

    public function reset(): void
    {
        $this->initializeQueryBuilder();
    }

    public function withOrderBy(OrderBy $orderBy): static
    {
        return $this->filter(function (QueryBuilder $qb) use ($orderBy): void {
            foreach ($orderBy->toArray() as $item) {
                [$field, $direction] = $item;
                $qb->addOrderBy(sprintf("%s.{$field}", $this->alias()), $direction);
            }
        });
    }

    protected function repository(): EntityRepository
    {
        return $this->em->getRepository($this->entityClass());
    }

    protected function initializeQueryBuilder(): void
    {
        $this->queryBuilder = $this->em->createQueryBuilder()
            ->select($this->alias())
            ->from($this->entityClass(), $this->alias())
        ;
    }

    protected function filter(callable $filter): static
    {
        $cloned = clone $this;
        $filter($cloned->queryBuilder);

        return $cloned;
    }

    protected function query(): QueryBuilder
    {
        return clone $this->queryBuilder;
    }

    /**
     * @return class-string
     */
    abstract protected function entityClass(): string;

    protected function alias(): string
    {
        return 'e';
    }
}
