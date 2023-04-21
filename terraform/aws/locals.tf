locals {
  # project_name = "${local.prefix}project"
  prefix = "ashdavies-"
  domain = "ashdavies.online"

  mysql = {
    user    = var.mysql_user
    pwd     = var.mysql_pwd
    version = var.mysql_version
  }

  redis = {
    version = var.redis_version
  }

  image = {
    repo = "ashdavies-ecr"
    tag  = "latest"
  }

  availability_zones = var.availability_zone_count
  instance_count     = var.container_count
}
