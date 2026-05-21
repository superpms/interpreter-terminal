# Vendor Install Hook

`pms\program\vendorInstallHook\VendorInstallHookCommand` is the built-in command mounted by this package.

## Command Name

```php
protected string $name = "vendor:install:hook";
```

It extends `TerminalCommandApp` and declares an empty `validate` array.

## Purpose

The command scans Composer's installed package metadata and applies the first supported `extra.pms` install action it finds for each package. It is a package installation helper, not a business command.

## Metadata Source

The command reads:

```php
Path::getRoot('vendor/composer/installed.json')
```

It expects Composer's installed metadata to contain a `packages` array. For each package, it checks whether:

```php
$package['extra']['pms']
```

exists.

## Supported Actions

For a single package, action selection follows the current source order:

1. `dir`
2. `config`
3. `copy`

The implementation uses `if / else if`, so if a package declares more than one of these keys, only the first key in that order is processed.

### dir

When `extra.pms.dir` exists, the command creates the declared directory or directories under the project root.

```json
{
  "extra": {
    "pms": {
      "dir": ["runtime/example"]
    }
  }
}
```

### config

When `extra.pms.config` exists, the command copies package files into the host config directory.

This package declares:

```json
{
  "extra": {
    "pms": {
      "config": {
        "command": "resource/config.php"
      }
    }
  }
}
```

The destination is:

```php
Path::getConfig($key . '.php')
```

The copy only happens if the destination file does not already exist.

### copy

When `extra.pms.copy` exists, the command copies package files into project-root-relative paths.

The copy only happens if the destination file does not already exist.

## Install Path Resolution

For package source files, the command builds a vendor package root from:

```php
Path::getRoot('vendor/composer/', $package['install-path'])
```

Then it joins that path with the declared source path.

## Safety Model

The command avoids overwriting existing files. For the selected action, it creates missing directories and copies missing files only.

It does not validate business-level config content and does not run package-specific migration logic. Package authors should keep `extra.pms` install actions limited to simple project scaffolding.
