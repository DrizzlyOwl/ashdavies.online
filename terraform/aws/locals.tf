locals {
  account_id   = data.aws_caller_identity.current.account_id
  prefix       = "ashdavies-"
  project_name = "${local.prefix}project"
  domain       = "ashdavies.online"
  github = {
    repo   = "drizzlyowl/ashdavies.online"
    branch = "master"
  }

  mysql = {
    name    = var.mysql_name
    user    = var.mysql_user
    pwd     = var.mysql_pwd
    version = var.mysql_version
  }

  region = "eu-west-2"

  redis = {
    enabled = false
    version = var.redis_version
  }

  enable_waf = false

  ip_addr = var.ip_addr

  image = {
    repo = "ashdavies-ecr"
    tag  = "latest"
  }

  wordpress = {
    enable_redis = true
    debug_mode   = "0"
  }

  container_env = {
    "WORDPRESS_DB_HOST"     = aws_lightsail_database.database.master_endpoint_address,
    "WORDPRESS_DB_USER"     = local.mysql.user,
    "WORDPRESS_DB_PASSWORD" = local.mysql.pwd,
    "WORDPRESS_DB_NAME"     = local.mysql.name,
    "WORDPRESS_DEBUG"       = local.wordpress.debug_mode,
    "WORDPRESS_CONFIG_EXTRA" = templatefile("./templates/wp-config.tftpl", {
      domain : local.domain,
      redis : {
        disabled : local.wordpress.enable_redis ? false : true,
        port : 6379
        host : "${aws_lightsail_container_service.container.private_domain_name}"
      }
    })
  }

  instance_count = var.container_count

  tags = {
    "Project Name" = local.project_name
  }
}
