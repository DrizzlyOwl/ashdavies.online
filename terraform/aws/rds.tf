resource "aws_lightsail_database" "database" {
  relational_database_name = "${local.prefix}db"
  availability_zone        = data.aws_availability_zones.az.names[0]
  master_database_name     = local.mysql.name
  master_password          = local.mysql.pwd
  master_username          = local.mysql.user
  blueprint_id             = "mysql_${local.mysql.version}"
  bundle_id                = "micro_1_0"
}
