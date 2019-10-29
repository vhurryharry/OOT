# 9. Use a monorepo

Date: 2019-10-29

## Status

Accepted

## Context

This web application actually contains 3 smaller applications: a
frontend application, a backend control panel application and a
backend-for-frontend API to power both.

They all share similar tooling, dependencies, libraries and, most
importantly, provisioning and deployment processes.

## Decision

We decided to use a single repository for all applications. This
technique is known as [monorepo][1] and is widely used across the
industry in order to simplify dependency management,
collaboration across multiple teams and ease of build/deployment.

## Consequences

Since this is a small web application, none of the known issues of
the [monorepo][1] technique are significant.

[1]: https://en.wikipedia.org/wiki/Monorepo
