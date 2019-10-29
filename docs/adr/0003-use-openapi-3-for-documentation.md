# 3. Use OpenAPI 3 for documentation

Date: 2019-10-29

## Status

Accepted

## Context

We need a standard documentation format for the REST API.

## Decision

We will use OpenAPI 3 for the documentation of this API. Some tools
are necessary to keep the documentation in good shape, therefore we
will use [speccy][2] to validate the documentation, and [ReDoc][2] to
generate a publishable HTML version.

## Consequences

The [OpenAPI Specification][3] defines a standard, programming
language-agnostic interface description for REST APIs, which allows
both humans and computers to discover and understand the capabilities
of a service without requiring access to source code, additional
documentation, or inspection of network traffic.

Standards help provide a common framework of communication and
development, and ground us in picking the right tools based on a
specific need.

With proper documentation, we are able to reduce dependencies between
different teams working on a certain API, increasing time-to-market.

[1]: https://github.com/wework/speccy
[2]: https://github.com/Rebilly/ReDoc
[3]: https://github.com/OAI/OpenAPI-Specification
