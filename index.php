<?php

use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

require_once "vendor/autoload.php";

// region clear database if it exists
@unlink('db.sqlite');
// endregion

// region setup doctrine
$config = Setup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/src'],
    isDevMode: true,
    cache: DoctrineProvider::wrap(new ArrayAdapter()),
);

$conn = [
    'driver' => 'pdo_sqlite',
    'path' => 'db.sqlite',
];

$em = EntityManager::create($conn, $config);
// endregion

// region update database schema
$schemaTool = new SchemaTool($em);
$schemaTool->updateSchema($em->getMetadataFactory()->getAllMetadata());
// endregion

// region persist required entities
$author = new App\Author();
$book = new App\Book($author, '978-0-316-12908-4');
$user = new App\User();
$account = new App\Account($user);

$em->persist($author);
$em->persist($book);
$em->persist($user);
$em->persist($account);
$em->flush();
// endregion

// region clear instance pool, simulate new request
$em->clear();
// endregion

/** @var App\Book $book */
$book = $em->getRepository(App\Book::class)->findOneBy(['isbn' => '978-0-316-12908-4']);
$account = $em->getRepository(App\Account::class)->find(1);
$user = $em->getRepository(App\User::class)->find(1);
$book->test($user);
