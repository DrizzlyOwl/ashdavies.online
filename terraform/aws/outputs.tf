output "container_registry_url" {
  value = aws_ecr_repository.ecr.repository_url
}

output "connection_strings" {
  value = {
    mysql : "${aws_db_instance.mysql.address}:${aws_db_instance.mysql.port}"
    redis : "${aws_elasticache_cluster.redis.cache_nodes[0].address}:${aws_elasticache_cluster.redis.cache_nodes[0].port}"
    load_balancer : aws_alb.lb.dns_name
  }
}

output "availability_zones" {
  value = data.aws_availability_zones.az.names
}