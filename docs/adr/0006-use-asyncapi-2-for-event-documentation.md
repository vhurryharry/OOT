# 6. Use AsyncAPI 2 for event documentation

Date: 2019-10-29

## Status

Accepted

## Context

We need a standard documentation format for events that are going to
be published from the application.

## Decision

We will use [AsyncAPI 2][1] for the documentation of events published
from this application. The format is similar to the OpenAPI
standard already implemented in this service. We will use
[AsyncAPI Generator][2] to generate human-readable documentation
from the spec file.

## Consequences

The [AsyncAPI Specification][1] defines a standard, programming
language-agnostic interface description for event-based asynchronous
APIs, which allows both humans and computers to discover and
understand the capabilities of a service without requiring access to
the source code, additional documentation, or inspection of network
traffic.

Standards help provide a common framework of communication and
development, and ground us in picking the right tools based on a
specific need.

With proper documentation, we are able to reduce dependencies between
different teams working on a certain API, increasing time-to-market.

[1]: https://www.asyncapi.com/docs/specifications/2.0.0-rc1/
[2]: https://github.com/asyncapi/generator
