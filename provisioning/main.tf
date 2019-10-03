# Required params
variable "digital_ocean_token" {}
variable "app_domain" {}

# Optional params
variable "public_key" {
  default = "~/.ssh/id_rsa.pub"
}

variable "private_key" {
  default = "~/.ssh/id_rsa"
}

provider "digitalocean" {
  token = "${var.digital_ocean_token}"
}

# Setup SSH key
resource "digitalocean_ssh_key" "web01-droplet-ssh" {
  name = "web01-droplet-ssh"
  public_key = "${file(var.public_key)}"
}

# Setup web application servers
resource "digitalocean_droplet" "web01" {
  name = "web01"
  region = "nyc1"
  image = "ubuntu-18-04-x64"
  size = "s-1vcpu-1gb"
  private_networking = true
  monitoring = true
  ssh_keys = ["${digitalocean_ssh_key.web01-droplet-ssh.fingerprint}"]
  tags = ["web"]

  connection {
    user = "root"
    type = "ssh"
    private_key = "${file(var.private_key)}"
    timeout = "2m"
    host = "${digitalocean_droplet.web01.ipv4_address}"
  }

  provisioner "local-exec" {
    command = "printf '[school]\n${digitalocean_droplet.web01.ipv4_address} ansible_python_interpreter=/usr/bin/python3' app_domain=${var.app_domain} app_email=admin@oliveoilschool.org > ansible/hosts/production"
  }
}

output "web01_ip" {
  value = digitalocean_droplet.web01.ipv4_address
}
