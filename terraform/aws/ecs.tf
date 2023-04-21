resource "aws_ecs_cluster" "ecs-cluster" {
  name = "${local.prefix}cluster"

  setting {
    name  = "containerInsights"
    value = "enabled"
  }
}

resource "aws_ecs_task_definition" "ecs-task" {
  family             = "${local.prefix}family"
  execution_role_arn = aws_iam_role.role.arn

  container_definitions = jsonencode([
    {
      # healthCheck = {
      #   command  = ["CMD-SHELL", "curl -f http://localhost/ || exit 1"],
      #   interval = 30
      # }

      name      = "${local.prefix}web"
      image     = "${aws_ecr_repository.ecr.repository_url}:${local.image.tag}"
      essential = true
      cpu       = 1024
      memory    = 2048

      environment = [
        { "name" : "WORDPRESS_DB_HOST", "value" : aws_db_instance.mysql.endpoint },
        { "name" : "WORDPRESS_DB_USER", "value" : aws_db_instance.mysql.username },
        { "name" : "WORDPRESS_DB_PASSWORD", "value" : local.mysql.pwd },
        { "name" : "WORDPRESS_DB_NAME", "value" : aws_db_instance.mysql.db_name },
        # { "name" : "WP_HOME", "value" : aws_alb.lb.dns_name },
        # { "name" : "WP_SITEURL", "value" : aws_alb.lb.dns_name },
        # { "name" : "WP_REDIS_HOST", "value" : "${aws_elasticache_cluster.redis.cache_nodes[0].address}:${aws_elasticache_cluster.redis.cache_nodes[0].port}" }
      ],

      portMappings = [
        {
          containerPort = 80
        }
      ]

      logConfiguration = {
          logDriver = "awslogs",
          options = {
            "awslogs-group": aws_cloudwatch_log_group.cw.name,
            "awslogs-region": "eu-west-2",
            "awslogs-stream-prefix": "ecs"
          }
        }
    }
  ])

  requires_compatibilities = ["FARGATE"] # Stating that we are using ECS Fargate
  network_mode             = "awsvpc"    # Using awsvpc as our network mode as this is required for Fargate
  memory                   = 2048        # Specifying the memory our container requires
  cpu                      = 1024        # Specifying the CPU our container requires
}

resource "aws_ecs_service" "ecs-service" {
  name            = "${local.prefix}service"
  cluster         = aws_ecs_cluster.ecs-cluster.arn
  desired_count   = local.instance_count
  launch_type     = "FARGATE"
  task_definition = aws_ecs_task_definition.ecs-task.arn

  depends_on = [
    aws_ecs_task_definition.ecs-task
  ]

  load_balancer {
    target_group_arn = aws_alb_target_group.tg.arn
    container_name   = "${local.prefix}web"
    container_port   = 80
  }

  network_configuration {
    assign_public_ip = true
    subnets          = [for subnet in aws_subnet.private : subnet.id]
    security_groups = [
      aws_security_group.sg.id
    ]
  }
}