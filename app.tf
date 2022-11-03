# Create new web app container
resource "digitalocean_app" "app" {
  spec {
    name   = "${local.prefix}app"
    region = "lon"

    domain {
      name = local.domain
      type = "PRIMARY"
    }

    service {
      name               = "${local.prefix}web"
      instance_count     = 1
      dockerfile_path = "Dockerfile"
      http_port = 80

      alert {
        value    = 75
        operator = "GREATER_THAN"
        window   = "TEN_MINUTES"
        rule     = "CPU_UTILIZATION"
      }

      routes {
        path = "/"
      }

      github {
        branch = "master"
        deploy_on_push = true
        repo = "DrizzlyOwl/${local.domain}"
      }

      health_check {
        http_path = "/"
        initial_delay_seconds = 10
        period_seconds = 5
        success_threshold = 2
        failure_threshold = 3
        timeout_seconds = 3
      }

      env {
        key = "WORDPRESS_DB_HOST"
        value = "${digitalocean_database_cluster.mysql.private_host}:${digitalocean_database_cluster.mysql.port}"
        type = "GENERAL"
      }
      env {
        key = "WORDPRESS_DB_USER"
        value = digitalocean_database_user.mysql-user.name
        type = "GENERAL"
      }
      env {
        key = "WORDPRESS_DB_PASSWORD"
        value = digitalocean_database_user.mysql-user.password
        type = "SECRET"
      }
      env {
        key = "WORDPRESS_DB_NAME"
        value = digitalocean_database_db.mysql-database.name
        type = "GENERAL"
      }
      env {
        key = "WORDPRESS_DEBUG"
        value = true
        type = "GENERAL"
      }
    }
  }
}
