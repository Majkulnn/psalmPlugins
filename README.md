# psalmPlugins

A collection of independent plugins that extend [Psalm](https://psalm.dev/) with additional static analysis capabilities.

The goal of this repository is to provide small, focused plugins that can be installed and configured independently.

## Requirements

- PHP `>=8.5 <8.6`
- Psalm `^6.11`

Individual plugins may introduce additional requirements. Check the documentation of the selected plugin before installing it.

## Available plugins

| Plugin | Description | Documentation |
|---|---|---|
| Doctrine Collections | Validates PHPDoc generics for Doctrine Collections in method return types and selected entity properties. | [Doctrine Collections](src/DoctrineCollection/README.md) |

## Installation

Install the package using Composer:

```bash
composer require majkuln/psalm-plugins
```

Then register the plugin in your Psalm configuration file (`psalm.xml`):

```xml
<plugins>
    <pluginClass class="App\DoctrineCollection\Plugin"/>
</plugins>
```

Make sure the plugin's dependencies are installed and that the plugin class is available to Psalm.

## Running Psalm

Run Psalm from your project directory:

```bash
vendor/bin/psalm
```

To run the project's test suite and code style checks, use the commands defined in the repository's `Makefile`.

## Development

The repository is organized around independent plugins. Each plugin should provide its own:

- implementation;
- Psalm issues and diagnostics;
- tests and fixtures;
- documentation.

Development dependencies and test tooling are maintained separately from runtime dependencies whenever possible.

## License

See the repository's license file for licensing information.