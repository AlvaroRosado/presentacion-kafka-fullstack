<?php

namespace App\Modules\Shared\Domain;

interface Paginator extends \Countable
{
    public function currentPage(): int;

    public function itemsPerPage(): int;

    public function totalPages(): int;

    public function changePage(int $page): void;

    public function changeMaxPerPage(int $itemsPerPage): void;

    public function autoIterator(): iterable;

    public function totalItems(): int;

    public function asIterator(): iterable;

    public function currentPageOffsetStart(): int;

    public function currentPageOffsetEnd(): int;

    public function hasNextPage(): bool;

    public function hasPreviousPage(): bool;

    public function nextPage(): ?int;

    public function previousPage(): ?int;
}
