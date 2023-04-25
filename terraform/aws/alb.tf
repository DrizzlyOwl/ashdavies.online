resource "aws_alb" "lb" {
  name               = "${local.prefix}lb"
  internal           = false
  load_balancer_type = "application"
  security_groups    = [aws_security_group.lb.id]
  subnets            = [for subnet in aws_subnet.public : subnet.id]
}

resource "aws_security_group" "lb" {
  vpc_id      = aws_vpc.vpc.id
  description = "Controls access to the ALB"

  ingress {
    protocol    = "tcp"
    from_port   = 80
    to_port     = 80
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    protocol    = "tcp"
    from_port   = 443
    to_port     = 443
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_alb_target_group" "lb" {
  name        = "${local.prefix}lb-tg"
  port        = 80
  protocol    = "HTTP"
  vpc_id      = aws_vpc.vpc.id
  target_type = "ip"

  health_check {
    path = "/health.txt"
  }
}

resource "aws_alb_listener" "http" {
  load_balancer_arn = aws_alb.lb.arn
  port              = 80
  protocol          = "HTTP"

  default_action {
    type             = "forward"
    target_group_arn = aws_alb_target_group.lb.arn
  }
}

resource "aws_alb_listener" "https" {
  load_balancer_arn = aws_alb.lb.arn
  port              = 443
  protocol          = "HTTPS"
  ssl_policy        = "ELBSecurityPolicy-2016-08"
  certificate_arn   = aws_acm_certificate_validation.tls.certificate_arn

  default_action {
    type             = "forward"
    target_group_arn = aws_alb_target_group.lb.arn
  }
}