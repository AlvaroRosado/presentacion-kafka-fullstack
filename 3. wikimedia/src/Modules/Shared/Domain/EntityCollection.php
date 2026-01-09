<?php

namespace App\Modules\Shared\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\Common\Collections\Criteria;

class EntityCollection implements DoctrineCollection
{
    protected ?Criteria $criteria = null;
    protected ArrayCollection|DoctrineCollection $collection;

    public function __construct(
        ArrayCollection|DoctrineCollection|array|null $collection = null,
    ) {
        if ($collection instanceof DoctrineCollection) {
            $this->collection = $collection;
        } elseif (is_array($collection)) {
            $this->collection = new ArrayCollection($collection);
        } else {
            $this->collection = new ArrayCollection();
        }
    }

    public static function fromArray(array $items): static
    {
        return new static(new ArrayCollection($items));
    }

    public function count(): int
    {
        return $this->getWrapped()->count();
    }

    public function getIterator(): \Traversable
    {
        return $this->getWrapped()->getIterator();
    }

    public function contains(mixed $element): bool
    {
        return $this->getWrapped()->contains($element);
    }

    public function isEmpty(): bool
    {
        return $this->getWrapped()->isEmpty();
    }

    public function containsKey(int|string $key): bool
    {
        return $this->getWrapped()->containsKey($key);
    }

    public function get(int|string $key): mixed
    {
        $wrapped = $this->getWrapped();

        return $wrapped->get($key);
    }

    public function getKeys(): array
    {
        return $this->getWrapped()->getKeys();
    }

    public function getValues(): array
    {
        return $this->getWrapped()->getValues();
    }

    public function toArray(): array
    {
        return $this->getWrapped()->toArray();
    }

    public function first(): mixed
    {
        return $this->getWrapped()->first();
    }

    public function last(): mixed
    {
        return $this->getWrapped()->last();
    }

    public function key(): int|string|null
    {
        return $this->getWrapped()->key();
    }

    public function current(): mixed
    {
        return $this->getWrapped()->current();
    }

    public function next(): mixed
    {
        return $this->getWrapped()->next();
    }

    public function slice(int $offset, ?int $length = null): array
    {
        return $this->getWrapped()->slice($offset, $length);
    }

    public function exists(\Closure $p): bool
    {
        return $this->getWrapped()->exists($p);
    }

    public function filter(\Closure $p): DoctrineCollection
    {
        return $this->getWrapped()->filter($p);
    }

    public function map(\Closure $func): DoctrineCollection
    {
        return $this->getWrapped()->map($func);
    }

    public function partition(\Closure $p): array
    {
        return $this->getWrapped()->partition($p);
    }

    public function forAll(\Closure $p): bool
    {
        return $this->getWrapped()->forAll($p);
    }

    public function indexOf(mixed $element): bool|int|string
    {
        return $this->getWrapped()->indexOf($element);
    }

    public function findFirst(\Closure $p): mixed
    {
        return $this->getWrapped()->findFirst($p);
    }

    public function reduce(\Closure $func, mixed $initial = null): mixed
    {
        return $this->getWrapped()->reduce($func, $initial);
    }

    public function add(mixed $element): void
    {
        $this->getWrapped()->add($element);
    }

    public function addIfNotExists(mixed $element): void
    {
        if (!$this->getWrapped()->contains($element)) {
            $this->getWrapped()->add($element);
        }
    }

    public function clear(): void
    {
        $this->getWrapped()->clear();
    }

    public function remove(int|string $key): mixed
    {
        $wrapped = $this->getWrapped();

        return $wrapped->remove($key);
    }

    public function removeElement(mixed $element): bool
    {
        return $this->getWrapped()->removeElement($element);
    }

    public function set(int|string $key, mixed $value): void
    {
        $wrapped = $this->getWrapped();
        $wrapped->set($key, $value);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->getWrapped()->offsetExists($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->getWrapped()->offsetGet($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->getWrapped()->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->getWrapped()->offsetUnset($offset);
    }

    public function reverse(): self
    {
        $items = $this->getWrapped()->toArray();

        return static::fromArray(array_reverse($items, true));
    }

    protected function getWrapped(): mixed
    {
        if (null === $this->criteria) {
            return $this->collection;
        }

        return $this->collection->matching($this->criteria); // @phpstan-ignore method.notFound
    }

    protected function matching(Criteria $criteria): static
    {
        $clone = clone $this;

        if (null === $clone->criteria) {
            $clone->criteria = $criteria;
        } else {
            $clone->criteria = $this->mergeCriteria($clone->criteria, $criteria);
        }

        return $clone;
    }

    public function findOrFail(\Closure $p, ?string $exceptionMessage = null): mixed
    {
        $entity = $this->getWrapped()->findFirst($p);

        if (!$entity) {
            throw new \DomainException($exceptionMessage ?? sprintf(
                '%s not found in %s',
                $this->inferEntityName(),
                static::class
            ));
        }

        return $entity;
    }

    /**
     * @throws \ReflectionException
     */
    private function inferEntityName(): string
    {
        $items = $this->getWrapped()->toArray();
        if ($items === []) {
            return 'Entity';
        }

        $first = reset($items);
        return new \ReflectionClass($first)->getShortName();
    }

    private function mergeCriteria(Criteria $criteria1, Criteria $criteria2): Criteria
    {
        $criteria = clone $criteria1;

        if (null !== $criteria2->getFirstResult()) {
            $criteria->setFirstResult($criteria2->getFirstResult());
        }

        if (null !== $criteria2->getMaxResults()) {
            $criteria->setMaxResults($criteria2->getMaxResults());
        }

        $where = $criteria2->getWhereExpression();
        if (null !== $where) {
            $criteria->andWhere($where);
        }

        $criteria->orderBy([
            ...$criteria->orderings(),
            ...$criteria2->orderings(),
        ]);

        return $criteria;
    }
}
