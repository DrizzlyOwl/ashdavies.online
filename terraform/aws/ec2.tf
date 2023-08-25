resource "aws_instance" "mysql" {
  ami               = data.aws_ami.ubuntu.id
  instance_type     = "t3.nano"
  availability_zone = data.aws_availability_zones.az.names[0]
  key_name          = local.keypair

  network_interface {
    network_interface_id = aws_network_interface.mysql.id
    device_index         = 0
  }

  user_data_replace_on_change = true
  user_data                   = templatefile("./userdata/mysql-client.tftpl", {})
}

resource "aws_instance" "nat" {
  ami               = data.aws_ami.ubuntu.id
  key_name          = local.keypair
  instance_type     = "t3.nano"
  availability_zone = data.aws_availability_zones.az.names[0]

  network_interface {
    network_interface_id = aws_network_interface.nat.id
    device_index         = 0
  }

  user_data_replace_on_change = true
  user_data = templatefile("./userdata/iptables.tftpl", {
    cidr_block : aws_vpc.vpc.cidr_block
  })
}
