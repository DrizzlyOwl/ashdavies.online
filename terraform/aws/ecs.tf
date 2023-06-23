resource "aws_ecs_cluster" "ecs-cluster" {
  name = "${local.project_name}-ecs-cluster"

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
      name      = "${local.prefix}web"
      image     = "${aws_ecr_repository.ecr.repository_url}:${local.image.tag}"
      essential = true
      cpu       = 512
      memory    = 1024
      healthCheck = {
        "Command" : ["CMD-SHELL", "curl -IfL http://localhost/health.txt || exit 1"],
        "Interval" : 5,
        "Timeout" : 2,
        "Retries" : 3
      }

      environment = local.container_env,

      portMappings = [
        {
          containerPort = 80
        }
      ]

      logConfiguration = {
        logDriver = "awslogs",
        options = {
          "awslogs-group" : aws_cloudwatch_log_group.cw.name,
          "awslogs-region" : "eu-west-2",
          "awslogs-stream-prefix" : "ecs"
        }
      }
    }
  ])

  requires_compatibilities = ["FARGATE"] # Stating that we are using ECS Fargate
  network_mode             = "awsvpc"    # Using awsvpc as our network mode as this is required for Fargate
  memory                   = 1024        # Specifying the memory our container requires
  cpu                      = 512         # Specifying the CPU our container requires
}

resource "aws_ecs_service" "ecs-service" {
  name            = "${local.project_name}-ecs-service"
  cluster         = aws_ecs_cluster.ecs-cluster.arn
  desired_count   = local.instance_count
  launch_type     = "FARGATE"
  task_definition = aws_ecs_task_definition.ecs-task.arn

  depends_on = [
    aws_ecs_task_definition.ecs-task
  ]

  load_balancer {
    target_group_arn = aws_alb_target_group.lb.arn
    container_name   = "${local.prefix}web"
    container_port   = 80
  }

  network_configuration {
    assign_public_ip = true
    subnets          = [for subnet in aws_subnet.private : subnet.id]
    security_groups = [
      aws_security_group.tasks.id
    ]
  }
}

# Traffic to the ECS cluster should only come from the ALB
resource "aws_security_group" "tasks" {
  name        = "${local.project_name}-sg-ecs"
  vpc_id      = aws_vpc.vpc.id
  description = "Allows inbound access from the ALB only"

  ingress {
    description     = "Allow INGRESS for HTTP on port 80"
    protocol        = "tcp"
    from_port       = 80
    to_port         = 80
    security_groups = [aws_security_group.lb.id]
  }

  egress {
    description = "Allow EGRESS for any on all ports"
    protocol    = "-1"
    from_port   = 0
    to_port     = 0
    cidr_blocks = ["0.0.0.0/0"]
  }
}