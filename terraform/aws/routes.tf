resource "aws_route_table" "table" {
  count  = local.availability_zones
  vpc_id = aws_vpc.vpc.id

  route {
    cidr_block     = "0.0.0.0/0"
    nat_gateway_id = element(aws_nat_gateway.gw[*].id, count.index)
  }
}

resource "aws_route_table_association" "table" {
  count          = local.availability_zones
  route_table_id = element(aws_route_table.table[*].id, count.index)
  subnet_id      = element(aws_subnet.private[*].id, count.index)
}

resource "aws_internet_gateway" "ig" {
  vpc_id = aws_vpc.vpc.id
}

resource "aws_route" "egress" {
  gateway_id             = aws_internet_gateway.ig.id
  route_table_id         = aws_vpc.vpc.main_route_table_id
  destination_cidr_block = "0.0.0.0/0"
}

resource "aws_nat_gateway" "gw" {
  count         = local.availability_zones
  allocation_id = element(aws_eip.eip[*].id, count.index)
  subnet_id     = element(aws_subnet.public[*].id, count.index)

  # To ensure proper ordering, it is recommended to add an explicit dependency
  # on the Internet Gateway for the VPC.
  depends_on = [aws_internet_gateway.ig]
}

resource "aws_eip" "eip" {
  count = local.availability_zones
  vpc   = true
  depends_on = [
    aws_internet_gateway.ig
  ]
}