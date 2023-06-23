locals {
  project_name = "${local.prefix}project"
  prefix       = "ashdavies-"
  domain       = "ashdavies.online"

  mysql = {
    user    = var.mysql_user
    pwd     = var.mysql_pwd
    version = var.mysql_version
  }

  region = "eu-west-2"

  redis = {
    version = var.redis_version
  }

  keypair = var.keypair
  ip_addr = var.ip_addr

  image = {
    repo = "ashdavies-ecr"
    tag  = "latest"
  }

  instance_count = var.container_count
}
