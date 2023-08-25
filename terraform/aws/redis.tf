resource "aws_elasticache_cluster" "redis" {
  count = local.redis.enabled ? 1 : 0

  availability_zone    = data.aws_availability_zones.az.names[0]
  cluster_id           = "${local.prefix}cluster"
  engine               = "redis"
  node_type            = "cache.t4g.micro"
  parameter_group_name = "default.redis${local.redis.version}"
  subnet_group_name    = aws_elasticache_subnet_group.redis.name
  security_group_ids   = [aws_security_group.redis.id]
  apply_immediately    = true
  num_cache_nodes      = 1
  engine_version       = "${local.redis.version}.0"
  port                 = 6379
}

resource "aws_elasticache_subnet_group" "redis" {
  name       = "${local.project_name}-redis-subnet-group"
  subnet_ids = [for subnet in aws_subnet.private : subnet.id]
}

resource "aws_security_group" "redis" {
  name        = "${local.project_name}-sg-redis"
  vpc_id      = aws_vpc.vpc.id
  description = "Controls access to the Redis cluster"

  ingress {
    description = "Allow INGRESS for REDIS on port 6379"
    protocol    = "tcp"
    from_port   = 6379
    to_port     = 6379
    cidr_blocks = [for subnet in aws_subnet.private : subnet.cidr_block]
  }
}