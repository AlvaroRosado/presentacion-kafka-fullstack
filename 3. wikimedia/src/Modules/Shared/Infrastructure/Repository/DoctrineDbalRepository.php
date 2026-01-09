<?php

namespace App\Modules\Shared\Infrastructure\Repository;

use App\Modules\Shared\Application\QueryResponse;
use App\Modules\Shared\Domain\EntityNotFoundException;
use App\Modules\Shared\Domain\OrderBy;
use App\Modules\Shared\Domain\Paginator;
use App\Modules\Shared\Domain\Repository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Doctrine\DBAL\QueryAdapter;
use Symfony\Contracts\Service\ResetInterface;

abstract class DoctrineDbalRepository implements Repository, ResetInterface
{
    protected QueryBuilder $queryBuilder;
    private ?int $page = null;
    private ?int $itemsPerPage = null;

    public function __construct(
        protected Connection $connection,
    ) {
        $this->initializeQueryBuilder();
    }

    protected function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }

    public function flush(): void
    {
    }

    abstract public function mapRowToResponse(array $row): QueryResponse;

    public function getIterator(): \Iterator
    {
        if (null !== $paginator = $this->paginator()) {
            foreach ($paginator->asIterator() as $row) {
                yield $this->mapRowToResponse($row);
            }
            return;
        }

        $results = $this->queryBuilder->executeQuery()->fetchAllAssociative();

        foreach ($results as $result) {
            yield $this->mapRowToResponse($result);
        }
    }


    public function asArray(): array
    {
        return iterator_to_array($this->getIterator());
    }

    public function getOneOrNull(): ?QueryResponse
    {
        $result = $this->queryBuilder->executeQuery()->fetchAssociative();

        if (!$result) {
            return null;
        }

        return $this->mapRowToResponse($result);
    }

    public function getOneOrFail(): QueryResponse
    {
        $result = $this->getOneOrNull();

        if (!$result) {
            throw EntityNotFoundException::forEntity(
                $this->entityTable(),
                $this->getQueryDebugInfo()
            );
        }

        return $result;
    }

    public function paginator(): ?Paginator
    {
        if (null === $this->page || null === $this->itemsPerPage) {
            return null;
        }

        $countQueryBuilderModifier = function (QueryBuilder $queryBuilder): QueryBuilder {
            $queryBuilder->select('COUNT(*)')
                ->setMaxResults(1)
                ->resetOrderBy()
            ;

            return $queryBuilder;
        };

        $doctrinePaginator = new DoctrinePaginator(new QueryAdapter($this->queryBuilder, $countQueryBuilderModifier));
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

    public function withOrderBy(OrderBy $orderBy): static
    {
        return $this->filter(function (QueryBuilder $qb) use ($orderBy): void {
            foreach ($orderBy->toArray() as $item) {
                [$field, $direction] = $item;
                $qb->addOrderBy(sprintf("%s.{$field}", $this->alias()), $direction);
            }
        });
    }

    public function withPagination(int $page, int $itemsPerPage): static
    {
        $cloned = clone $this;
        $cloned->page = $page;
        $cloned->itemsPerPage = $itemsPerPage;

        return $cloned;
    }

    public function count(): int
    {
        $paginator = $this->paginator() ?? $this->queryBuilder;

        return $paginator->count();
    }

    public function reset(): void
    {
        $this->initializeQueryBuilder();
    }

    public function initializeQueryBuilder(): void
    {
        $this->queryBuilder = $this->connection->createQueryBuilder()
            ->select('*')
            ->from($this->entityTable(), $this->alias())
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

    private function getQueryDebugInfo(): array
    {
        $parameters = [];
        $types = [];

        foreach ($this->queryBuilder->getParameters() as $key => $value) {
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

            $parameters[$key] = $formattedValue;

            $parameterTypes = $this->queryBuilder->getParameterTypes();
            $types[$key] = $parameterTypes[$key] ?? 'unknown';
        }

        return [
            'sql' => $this->queryBuilder->getSQL(),
            'parameters' => $parameters,
            'types' => $types,
        ];
    }

    abstract protected function entityTable(): string;

    protected function alias(): string
    {
        return 'e';
    }
}
