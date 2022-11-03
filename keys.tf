resource "digitalocean_ssh_key" "default" {
  name       = "Ash MBP"
  public_key = file("/Users/ash/.ssh/id_rsa.pub")
}
