<?php

namespace App\Modules\Shared\Infrastructure\Repository;

use App\Modules\Shared\Domain\Paginator;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;

final class DoctrinePaginator implements Paginator
{
    private Pagerfanta $pagerfanta;

    public function __construct(AdapterInterface $adapter)
    {
        $this->pagerfanta = new Pagerfanta($adapter);
    }

    public function count(): int
    {
        return $this->pagerfanta->count();
    }

    public function currentPage(): int
    {
        return $this->pagerfanta->getCurrentPage();
    }

    public function changeMaxPerPage(int $itemsPerPage): void
    {
        $this->pagerfanta->setMaxPerPage($itemsPerPage);
    }

    public function itemsPerPage(): int
    {
        return $this->pagerfanta->getMaxPerPage();
    }

    public function totalPages(): int
    {
        return $this->pagerfanta->getNbPages();
    }

    public function changePage(int $page): void
    {
        $this->pagerfanta->setCurrentPage($page);
    }

    public function autoIterator(): iterable
    {
        return $this->pagerfanta->autoPagingIterator();
    }

    public function totalItems(): int
    {
        return $this->pagerfanta->getNbResults();
    }

    public function asIterator(): iterable
    {
        return $this->pagerfanta->getCurrentPageResults();
    }

    public function currentPageOffsetStart(): int
    {
        return $this->pagerfanta->getCurrentPageOffsetStart();
    }

    public function currentPageOffsetEnd(): int
    {
        return $this->pagerfanta->getCurrentPageOffsetEnd();
    }

    public function hasNextPage(): bool
    {
        return $this->pagerfanta->hasNextPage();
    }

    public function hasPreviousPage(): bool
    {
        return $this->pagerfanta->hasPreviousPage();
    }

    public function nextPage(): ?int
    {
        return $this->pagerfanta->hasNextPage()
            ? $this->pagerfanta->getNextPage()
            : null;
    }

    public function previousPage(): ?int
    {
        return $this->pagerfanta->hasPreviousPage()
            ? $this->pagerfanta->getPreviousPage()
            : null;
    }
}
