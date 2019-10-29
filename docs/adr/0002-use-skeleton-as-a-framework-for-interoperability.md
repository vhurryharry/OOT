# 2. Use a common framework for interoperability

Date: 2019-10-29

## Status

Accepted

## Context

We need to be able to operate within JSM's ecosystem in sync with
the existing logging, tracing and monitoring standards.

## Decision

We will use [Symfony][1] and [Angular][2] as the base framework
for this project.

## Consequences

The Symfony framework provides a set of preset configurations for
tools that are essential within the PHP ecosystem: PHPUnit, Behat,
PHPStan. It also provides a PHP-CS-Fixer configuration with
the official PHP coding standards.

The Angular framework provides a set of preset modules for modern
frontend development. It also provides preset configuration for
the community coding standard for TypeScript and SASS.

Comforming to standards reduces friction in project maintenance,
specially between multiple and diverse contributors.

[1]: https://symfony.com/
[2]: https://angular.io/
