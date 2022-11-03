resource "digitalocean_database_cluster" "mysql" {
  name       = "${local.prefix}mysql"
  engine     = "mysql"
  version    = "8"
  size       = "db-s-1vcpu-1gb"
  region     = "lon1"
  node_count = 1

  # maintenance_window {
  #   day = "Wednesday"
  #   hour = "01:00"
  # }
}

resource "digitalocean_database_db" "mysql-database" {
  cluster_id = digitalocean_database_cluster.mysql.id
  name       = "wp_ashdavies"
}

resource "digitalocean_database_user" "mysql-user" {
  cluster_id = digitalocean_database_cluster.mysql.id
  name       = "ash"
}

resource "digitalocean_database_firewall" "mysql-fw" {
  cluster_id = digitalocean_database_cluster.mysql.id

    rule {
        type = "app"
        value = digitalocean_app.app.id
    }
}
