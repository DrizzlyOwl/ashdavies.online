resource "aws_elasticache_cluster" "redis" {
  cluster_id           = "${local.prefix}cluster"
  engine               = "redis"
  node_type            = "cache.t3.micro"
  parameter_group_name = "default.redis${local.redis.version}"
  num_cache_nodes      = 1
  engine_version       = "${local.redis.version}.0"
  port                 = 6379
}

resource "aws_elasticache_subnet_group" "redis" {
  name       = "${local.prefix}redis-subnet"
  subnet_ids = [for subnet in aws_subnet.private : subnet.id]
}