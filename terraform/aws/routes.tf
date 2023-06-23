resource "aws_route_table" "table" {
  vpc_id = aws_vpc.vpc.id

  route {
    cidr_block           = "0.0.0.0/0"
    network_interface_id = aws_network_interface.nat.id
  }

  route {
    cidr_block           = "0.0.0.0/0"
    network_interface_id = aws_network_interface.mysql.id
  }
}

resource "aws_route_table_association" "table" {
  for_each       = aws_subnet.private
  route_table_id = aws_route_table.table.id
  subnet_id      = each.value.id
}

resource "aws_internet_gateway" "ig" {
  vpc_id = aws_vpc.vpc.id
}

resource "aws_route" "egress" {
  gateway_id             = aws_internet_gateway.ig.id
  route_table_id         = aws_vpc.vpc.main_route_table_id
  destination_cidr_block = "0.0.0.0/0"
}

resource "aws_network_interface" "nat" {
  subnet_id         = element([for subnet in aws_subnet.public : subnet.id], 0)
  source_dest_check = false
  security_groups   = [aws_security_group.nat.id]
}

resource "aws_network_interface" "mysql" {
  subnet_id         = element([for subnet in aws_subnet.public : subnet.id], 0)
  source_dest_check = false
  security_groups   = [aws_security_group.ssh.id, aws_security_group.nat.id]
}

resource "aws_eip" "eip" {
  network_interface = aws_network_interface.nat.id
}

resource "aws_eip" "mysql" {
  network_interface = aws_network_interface.mysql.id
}