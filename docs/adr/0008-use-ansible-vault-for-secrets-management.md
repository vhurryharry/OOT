# 8. Use Ansible Vault for secrets management

Date: 2019-10-29

## Status

Accepted

## Context

Properly managing sensitive strings such as passwords and API keys,
also known as secrets, is difficult and error-prone. We want to avoid
having secrets sprawled across the infrastructure. We want to store
in a single, secure location, with easy access to audit logs and
ACL policies.

## Decision

All secrets will be stored in an encrypted configuration file,
managed by [Ansible Vault][1]. This file will be kept in version
control to facilitate provisioning and deployment, as well as
feature-specific releases.

[Ansible Vault][1] is a secrets management tool, a centralized
solution where the secrets are going to be stored in a secure way.
Vault centrally manages and enforces access to secrets and systems
based on trusted sources of application and user identity.

## Consequences

This is just one of the steps in securing this web application. It
drastically reduces the chances of sensitive data being leaked due
to human error and enforces a better security hygiene from the
engineering team.

[1]: https://docs.ansible.com/ansible/latest/user_guide/vault.html
