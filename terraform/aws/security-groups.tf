
resource "aws_security_group" "mysql" {
  name        = "${local.project_name}-sg-mysql"
  vpc_id      = aws_vpc.vpc.id
  description = "Controls access to the MySQL cluster"

  ingress {
    description = "Allow INGRESS for MYSQL on port 3306"
    protocol    = "tcp"
    from_port   = 3306
    to_port     = 3306
    cidr_blocks = [for subnet in aws_subnet.private : subnet.cidr_block]
  }

  ingress {
    description = "Allow INGRESS for MYSQL on port 3306 from jumpbox"
    protocol    = "tcp"
    from_port   = 3306
    to_port     = 3306
    cidr_blocks = ["${aws_eip.mysql.private_ip}/32"]
  }
}

resource "aws_security_group" "ssh" {
  name        = "${local.project_name}-sg-ssh"
  vpc_id      = aws_vpc.vpc.id
  description = "Controls access to the MySQL Jump box"

  ingress {
    description = "Allow INGRESS for SSH on port 22"
    protocol    = "tcp"
    from_port   = 22
    to_port     = 22
    cidr_blocks = [local.ip_addr]
  }
}

resource "aws_security_group" "nat" {
  name        = "${local.project_name}-sg-nat"
  vpc_id      = aws_vpc.vpc.id
  description = "Security group for NAT instance"

  ingress = [
    {
      description      = "Allow INGRESS from VPC"
      from_port        = 0
      to_port          = 0
      protocol         = "-1"
      cidr_blocks      = [aws_vpc.vpc.cidr_block]
      ipv6_cidr_blocks = []
      prefix_list_ids  = []
      security_groups  = []
      self             = true
    }
  ]

  egress = [
    {
      description      = "Allow ANY EGRESS"
      from_port        = 0
      to_port          = 0
      protocol         = "-1"
      cidr_blocks      = ["0.0.0.0/0"]
      ipv6_cidr_blocks = []
      prefix_list_ids  = []
      security_groups  = []
      self             = true
    }
  ]
}