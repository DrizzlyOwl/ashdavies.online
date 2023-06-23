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

  container_env = [
    {
      "name" : "WORDPRESS_DB_HOST",
      "value" : aws_db_instance.mysql.endpoint
    },
    {
      "name" : "WORDPRESS_DB_USER",
      "value" : aws_db_instance.mysql.username
    },
    {
      "name" : "WORDPRESS_DB_PASSWORD",
      "value" : local.mysql.pwd
    },
    {
      "name" : "WORDPRESS_DB_NAME",
      "value" : aws_db_instance.mysql.db_name
    },
    {
      "name" : "WORDPRESS_DEBUG",
      "value" : "1"
    },
    {
      "name" : "WORDPRESS_CONFIG_EXTRA",
      "value" : "define( 'WP_REDIS_PORT', ${aws_elasticache_cluster.redis.cache_nodes[0].port} );define( 'WP_REDIS_DISABLED', false );define( 'WP_REDIS_HOST', '${aws_elasticache_cluster.redis.cache_nodes[0].address}' );define( 'WP_HOME', 'https://${local.domain}' );define( 'WP_SITEURL', 'https://${local.domain}' );"
    }
  ]

  instance_count = var.container_count
}
