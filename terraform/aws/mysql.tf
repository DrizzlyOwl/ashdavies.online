resource "aws_db_instance" "mysql" {
  allocated_storage               = 10
  max_allocated_storage           = 20
  identifier                      = "${local.prefix}mysql"
  db_name                         = "wp_ashdavies"
  engine                          = "mysql"
  engine_version                  = local.mysql.version
  instance_class                  = "db.t3.micro"
  username                        = local.mysql.user
  password                        = local.mysql.pwd
  parameter_group_name            = "default.mysql${local.mysql.version}"
  db_subnet_group_name            = aws_db_subnet_group.default.name
  skip_final_snapshot             = true
  apply_immediately               = true
  backup_retention_period         = 3
  availability_zone               = data.aws_availability_zones.az.names[0]
  delete_automated_backups        = true
  enabled_cloudwatch_logs_exports = ["error", "slowquery"]
  vpc_security_group_ids          = [aws_security_group.mysql.id]
}

resource "aws_db_subnet_group" "default" {
  subnet_ids = [for subnet in aws_subnet.private : subnet.id]
}

resource "aws_security_group" "mysql" {
  vpc_id      = aws_vpc.vpc.id
  description = "Controls access to the MySQL cluster"

  ingress {
    protocol    = "tcp"
    from_port   = 3306
    to_port     = 3306
    cidr_blocks = [for subnet in aws_subnet.private : subnet.cidr_block]
  }
}