# Doctrine overwrites `Book::$owner`

## Prerequisites

 * Two entities in one-to-many relation (`Author` and `Book` in this example)
 * **This is where the bug manifests itself**: The „many” side of the relation has a many-to-one relation to a yet another entity (`Book::$owner` to `User`)
 * The aforementioned relation is eagerly loaded (`src/User.php:21`)
 * Another, unrelated entity has a same many-to-one relation (`Account::$user` to `User`). As far as I understand the bug, this is only required, so we get a proxy of the entity instead of the entity itself.

## Test scenario

 * There is one of each entity in the database. The bug manifests itself during hydration
 * We fetch the `Book` entity using a non-pk field (`Book::$isbn`)
 * We fetch the `Account` (container) class to get the `User` entity as a proxy
 * We assign the user entity to a previously empty `User::$owner` field
 * We fetch the `Author::$books` collection, and hydrate it (`toArray()`)

## Expected behaviour

 * `Book::$owner` field is set to the previously used `User` proxy class

## Actual behaviour

 * `Book::$owner` is empty

The field is overwritten in: `vendor/doctrine/orm/lib/Doctrine/ORM/Internal/Hydration/ObjectHydrator.php:450`
