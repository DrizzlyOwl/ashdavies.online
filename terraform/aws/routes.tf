resource "aws_internet_gateway" "ig" {
  vpc_id = aws_vpc.vpc.id
}

resource "aws_route_table" "public" {
  vpc_id = aws_vpc.vpc.id
}

resource "aws_route" "public" {
  route_table_id         = aws_route_table.public.id
  destination_cidr_block = "0.0.0.0/0"
  gateway_id             = aws_internet_gateway.ig.id
}

resource "aws_route_table_association" "public" {
  for_each       = aws_subnet.public
  route_table_id = aws_route_table.public.id
  subnet_id      = each.value.id
}

######
resource "aws_route_table" "private" {
  vpc_id = aws_vpc.vpc.id
}

resource "aws_route" "private" {
  route_table_id         = aws_route_table.private.id
  destination_cidr_block = "0.0.0.0/0"
  network_interface_id   = aws_network_interface.nat.id
}

resource "aws_route_table_association" "private" {
  for_each       = aws_subnet.private
  route_table_id = aws_route_table.private.id
  subnet_id      = each.value.id
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