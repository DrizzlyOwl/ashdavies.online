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

  wordpress = {
    enable_redis = true
    debug_mode = "0"
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
      "value" : local.wordpress.debug_mode
    },
    {
      "name" : "WORDPRESS_CONFIG_EXTRA",
      "value" : templatefile("./templates/wp-config.tftpl", {
        domain : local.domain
        redis : {
          host : aws_elasticache_cluster.redis.cache_nodes[0].address
          port : aws_elasticache_cluster.redis.cache_nodes[0].port
          disabled : local.wordpress.enable_redis
        }
      })
    }
  ]

  instance_count = var.container_count
}
