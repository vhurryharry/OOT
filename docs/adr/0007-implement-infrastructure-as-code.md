# 7. Implement Infrastructure as Code

Date: 2019-10-29

## Status

Accepted

## Context

Traditionally, the infrastructure setup of a web application used to
take a long time, with a lot of manual processes and tweaking. Most
of the time, none of those processes were documented, and in case of
failure, recreating servers was cumbersome and expensive.

Automatically managing and provisioning the technology stack
necessary to run this web application is essential to its success.

## Decision

We will implement Infrastructure as Code, using free and open source
tools that are industry standard. For cloud provisioning and
configuration orchestration, we will use [Terraform][1]. For
configuration management we will use [Ansible][2].

[Terraform][1] is a tool for developing, changing and versioning
infrastructure safely and efficiently. Terraform can manage existing
and popular service providers as well as custom in-house solutions.

[Ansible][2] is a configuration management platform that automates
storage, servers, and networking. When you use Ansible to
configure these components, difficult manual tasks become repeatable
and less vulnerable to error.

## Consequences

Infrastructure as Code can help avoid cloud deployment
inconsistencies, increase developer productivity, and lower costs.

System definition files are versioned right alongside the product
code. Reverting to an old version due to a bug is no more difficult
than finding the correct version control commit and spinning up a
new deployment using the included configuration.

Dependencies, performance tweaks, security configuration: every piece
that makes up the entire infrastructure of the web application is
properly declared and fully automated.

[1]: https://www.terraform.io/
[2]: https://www.ansible.com/
