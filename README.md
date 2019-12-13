# Olive Oil School

Web application for e-learning.

## Usage

To start working on the project:

    $ npm install
    
    $ cd backend
    $ composer install
    
    $ cd admin
    $ npm install
    
To run the backend:
    
    $ cd backend
    $ composer run serve
    
To run the admin panel:
    
    $ cd admin
    $ npm start

## Running tests

To run the project tests and validate the coding standards:

    $ composer test

## Documentation

This project follows the OpenAPI 3.0 standard. You can find the
specification at `docs/openapi.yaml`. Tools are included to build
the documentation:

    $ npm run build:docs
    $ firefox dist/index.html

## Provisioning

This project uses immutable infrastructure and treats infrastructure as code.
We use Terraform for automating infrastructure provisioning and Ansible
for managing server configuration.

In order to setup cloud infrastructure:

    $ cd provisioning/
    $ terraform init
    $ terraform apply -var "digital_ocean_token=FOO" -var "app_domain=dev.oliveoilschool.org"

The included playbook is responsible for defining an optimized application
server on top of Ubuntu LTS. To run the playbook for a production server:

    $ pip install ansible
    $ ansible-playbook -i provisioning/ansible/hosts/production provisioning/ansible/playbook.yml -e "deploy_ssh_key=~/.ssh/id_rsa.pub" --ask-vault-pass
