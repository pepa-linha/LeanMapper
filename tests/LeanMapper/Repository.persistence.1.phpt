<?php

declare(strict_types=1);

use LeanMapper\Repository;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

//////////

/**
 * @property int $id
 * @property string $name
 * @property string|null $web
 */
class Author extends LeanMapper\Entity
{
}

class AuthorRepository extends Repository
{

    public function findAll()
    {
        return $this->createEntities(
            $this->connection->select('*')->from($this->getTable())->fetchAll()
        );
    }

}

//////////

$authorRepository = new AuthorRepository($connection, $mapper, $entityFactory);

$authors = $authorRepository->findAll();

$author = $authors[3];

$author->detach();

Assert::exception(
    function () use ($authorRepository, $author) {
        $authorRepository->persist($author);
    },
    '\Dibi\DriverException',
    'UNIQUE constraint failed: author.id'
);

//////////

$author->id = 6;
$author->name = 'John Doe';

Assert::true($author->isDetached());

$authorRepository->persist($author);

Assert::false($author->isDetached());

Assert::equal('John Doe', $authors[3]->name);

//////////

$author = new Author(
    [
        'name' => 'Steve Lee',
    ]
);

$authorRepository->persist($author);

Assert::equal(7, $author->id);

$authorRepository->persist($author);

Assert::equal(7, $author->id);

//////////

Assert::exception(
    function () use ($authorRepository, $author) {
        $author->id = 8;
    },
    'LeanMapper\Exception\InvalidArgumentException',
    "ID can only be set in detached rows."
);
