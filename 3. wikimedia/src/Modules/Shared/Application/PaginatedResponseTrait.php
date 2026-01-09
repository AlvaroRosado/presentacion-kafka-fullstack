<?php

namespace App\Modules\Shared\Application;

trait PaginatedResponseTrait
{
    public function getTotal(): int
    {
        return $this->paginator->totalItems();
    }

    public function getPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function getPerPage(): int
    {
        return $this->paginator->itemsPerPage();
    }

    public function getTotalPages(): int
    {
        return $this->paginator->totalPages();
    }

    public function toArray(): array
    {
        return [
            'data' => $this->getData(),
            'pagination' => [
                'total' => $this->paginator->totalItems(),
                'page' => $this->paginator->currentPage(),
                'perPage' => $this->paginator->itemsPerPage(),
                'totalPages' => $this->paginator->totalPages(),
            ]
        ];
    }

    public function hasNextPage(): bool
    {
        return $this->paginator->currentPage() < $this->paginator->totalPages();
    }

    public function hasPreviousPage(): bool
    {
        return $this->paginator->currentPage() > 1;
    }

    public function isEmpty(): bool
    {
        return $this->paginator->count() === 0;
    }

    public function count(): int
    {
        return $this->paginator->count();
    }

    abstract public function getData(): array;

    protected function getMeta(): array
    {
        return [];
    }
}
