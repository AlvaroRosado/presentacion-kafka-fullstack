<?php

namespace App\Modules\Shared\Domain;

/**
 * @template T
 */
interface Repository extends \IteratorAggregate, \Countable
{
    /**
     * @return \Iterator<int, T>
     */
    public function getIterator(): \Iterator;

    /**
     * @return T[]
     */
    public function asArray(): array;

    /**
     * @return T
     * @throws EntityNotFoundException
     */
    public function getOneOrFail(): object;

    /**
     * @return T|null
     */
    public function getOneOrNull(): ?object;

    public function count(): int;

    public function paginator(): ?Paginator;
    public function flush(): void;

    public function withPagination(int $page, int $itemsPerPage): static;

    public function withOrderBy(OrderBy $orderBy): static;

    public function withoutPagination(): static;
}
