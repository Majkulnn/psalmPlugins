# Doctrine Collections Psalm Plugin

A Psalm plugin that validates PHPDoc generic declarations for Doctrine Collections.

The plugin currently provides two checks:

1. Methods whose native return type is `Doctrine\Common\Collections\Collection` or `Doctrine\Common\Collections\ArrayCollection`.
2. Doctrine entity properties mapped with `OneToMany` or `ManyToMany` attributes.

Both checks require PHPDoc to declare two generic parameters: the collection key type and the collection element type.

## Requirements

- PHP `>=8.5 <8.6`
- Psalm `^6.11`
- `doctrine/collections`

Doctrine ORM is required for the plugin's development fixtures and tests. It is not a runtime dependency of the plugin.

## Installation

Install the package:

```bash
composer require majkuln/psalm-plugins
```

Make sure `doctrine/collections` is available in your project.

## Configuration

Register the plugin in your Psalm configuration file (`psalm.xml`):

```xml
<plugins>
    <pluginClass class="App\DoctrineCollection\Plugin"/>
</plugins>
```

The plugin is loaded by Psalm and registers its class-like analysis hook.

## What is validated?

### 1. Collection-returning methods

The plugin checks methods with a native return type of:

- `Doctrine\Common\Collections\Collection`
- `Doctrine\Common\Collections\ArrayCollection`

The check applies regardless of whether the method belongs to a Doctrine entity, repository, service, or another class.

The PHPDoc must declare two generic parameters.

**Valid examples:**

```php
use Doctrine\Common\Collections\Collection;

/**
 * @return Collection<int, Order>
 */
public function getOrders(): Collection
{
    // ...
}
```

```php
/**
 * @return Collection<string, Order>
 */
public function getOrdersByCode(): Collection
{
    // ...
}
```

```php
/**
 * @return Collection<int, mixed>
 */
public function getItems(): Collection
{
    // ...
}
```

```php
/**
 * @return Collection<int, Order>&Selectable
 */
public function getSelectableOrders(): Collection
{
    // ...
}
```

**Invalid examples:**

Missing PHPDoc:

```php
public function getOrders(): Collection
{
    // ...
}
```

Missing generic parameters:

```php
/** @return Collection */
public function getOrders(): Collection
{
    // ...
}
```

Only one generic parameter:

```php
/** @return Collection<int> */
public function getOrders(): Collection
{
    // ...
}
```

The plugin reports missing generic information when the PHPDoc is absent or does not provide both collection generic parameters.

### 2. Doctrine entity properties

The plugin also checks collection properties in Doctrine entities when they use either of these ORM attributes:

- `#[ORM\OneToMany(...)]`
- `#[ORM\ManyToMany(...)]`

The property must use one of the supported native collection types and declare two generic parameters in its PHPDoc.

**Valid example:**

```php
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Customer
{
    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class)]
    public Collection $orders;
}
```

The following is also valid:

```php
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Customer
{
    /**
     * @var ArrayCollection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class)]
    public ArrayCollection $orders;
}
```

A property without the required generic PHPDoc is reported:

```php
#[ORM\OneToMany(targetEntity: Order::class)]
public Collection $orders;
```

Properties without `OneToMany` or `ManyToMany` attributes are ignored by this check.

## Supported collection types

The plugin recognizes these collection types:

- `Doctrine\Common\Collections\Collection`
- `Doctrine\Common\Collections\ArrayCollection`

Both are supported for method return types and entity properties.

## Generic parameters

A collection PHPDoc must contain two generic parameters:

```php
Collection<TKey, TValue>
```

- `TKey` describes the collection key.
- `TValue` describes the collection element.

The plugin requires both parameters to be present. It does not impose an additional key-type restriction.

For example, all of the following are accepted by the plugin:

```php
Collection<int, Order>
Collection<string, Order>
Collection<array-key, Order>
Collection<int, mixed>
```

Psalm may independently report generic type problems according to its own templates and type rules. Those diagnostics are separate from this plugin's checks.

## Intersection types and Selectable

Intersection types are supported.

For example:

```php
/**
 * @return Collection<int, Order>&Selectable
 */
public function getOrders(): Collection
{
    // ...
}
```

The plugin checks the generic parameters of the `Collection` type.

The additional interface, such as `Selectable`, is optional. Its generic parameters are not validated by this plugin.

The presence of an additional interface must not cause an otherwise valid `Collection` declaration to fail validation.

## Diagnostics

The plugin provides diagnostics for missing generic information:

- `MissingCollectionGeneric` — a method's collection return PHPDoc does not provide both generic parameters.
- `MissingCollectionPropertyGeneric` — a mapped collection property does not provide both generic parameters.

Psalm may also report its own generic-related diagnostics. These are independent of the plugin's diagnostics.

## Current limitations

The current implementation is intentionally limited to the following scope:

- Doctrine ORM PHP attributes are supported for property mapping.
- Only `OneToMany` and `ManyToMany` properties are checked.
- Annotation-based and XML-based Doctrine mappings are not supported.
- Other association types are not checked.
- The plugin does not compare the PHPDoc element type with Doctrine's `targetEntity`.
- The plugin does not validate generic parameters of additional intersection types such as `Selectable`.
- The plugin does not automatically generate or repair PHPDoc.
- The plugin does not replace Psalm's built-in generic type validation.

## Development and tests

The repository contains fixtures for the plugin's analysis rules.

To run the fixture analysis:

```bash
rm -rf /var/cache/psalm-fixtures
vendor/bin/psalm --config=psalm-fixtures.xml
```

Doctrine ORM is installed as a development dependency for the entity fixtures and tests.

For the full project checks, use the commands provided by the repository's `Makefile`.

## Related work

This plugin is part of the [psalmPlugins](https://github.com/Majkulnn/psalmPlugins) repository.

The broader scope and implementation tasks are tracked in:

- [Issue #1 — Doctrine Collections PHPDoc validation](https://github.com/Majkulnn/psalmPlugins/issues/1)
- [Issue #7 — Set up Doctrine Collections Psalm plugin](https://github.com/Majkulnn/psalmPlugins/issues/7)
- [Issue #8 — Validate PHPDoc generics on Collection-returning methods](https://github.com/Majkulnn/psalmPlugins/issues/8)
- [Issue #9 — Validate Doctrine collection properties](https://github.com/Majkulnn/psalmPlugins/issues/9)