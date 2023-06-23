resource "aws_vpc" "vpc" {
  cidr_block = "10.1.0.0/16"
}

resource "aws_subnet" "public" {
  for_each                = toset(data.aws_availability_zones.az.names)
  availability_zone       = each.value
  vpc_id                  = aws_vpc.vpc.id
  cidr_block              = cidrsubnet(aws_vpc.vpc.cidr_block, 8, index(data.aws_availability_zones.az.names, each.value) + 10)
  map_public_ip_on_launch = true
}

resource "aws_subnet" "private" {
  for_each                = toset(data.aws_availability_zones.az.names)
  availability_zone       = each.value
  vpc_id                  = aws_vpc.vpc.id
  cidr_block              = cidrsubnet(aws_vpc.vpc.cidr_block, 8, index(data.aws_availability_zones.az.names, each.value) + 1)
  map_public_ip_on_launch = false
}
