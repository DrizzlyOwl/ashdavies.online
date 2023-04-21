resource "aws_db_instance" "mysql" {
  allocated_storage     = 10
  max_allocated_storage = 20
  identifier            = "${local.prefix}mysql"
  db_name               = "wp_ashdavies"
  engine                = "mysql"
  engine_version        = local.mysql.version
  instance_class        = "db.t3.micro"
  username              = local.mysql.user
  password              = local.mysql.pwd
  parameter_group_name  = "default.mysql${local.mysql.version}"
  skip_final_snapshot   = true
}