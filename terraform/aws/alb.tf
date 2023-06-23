resource "aws_alb" "lb" {
  name                       = "${local.project_name}-alb"
  internal                   = false
  load_balancer_type         = "application"
  security_groups            = [aws_security_group.lb.id]
  subnets                    = [for subnet in aws_subnet.public : subnet.id]
  drop_invalid_header_fields = true
}

resource "aws_security_group" "lb" {
  name        = "${local.project_name}-sg-alb"
  vpc_id      = aws_vpc.vpc.id
  description = "Controls access to the ALB"

  ingress {
    description = "Allow INGRESS for HTTP on port 80"
    protocol    = "tcp"
    from_port   = 80
    to_port     = 80
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    description = "Allow INGRESS for HTTPS on port 443"
    protocol    = "tcp"
    from_port   = 443
    to_port     = 443
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    description = "Allow EGRESS for any on all ports"
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_alb_target_group" "lb" {
  name        = "${local.project_name}-tg-alb"
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
    type = "redirect"

    redirect {
      protocol    = "HTTPS"
      port        = 443
      status_code = "HTTP_301"
    }
  }
}

resource "aws_alb_listener" "https" {
  load_balancer_arn = aws_alb.lb.arn
  port              = 443
  protocol          = "HTTPS"
  certificate_arn   = aws_acm_certificate_validation.tls.certificate_arn

  default_action {
    type             = "forward"
    target_group_arn = aws_alb_target_group.lb.arn
  }
}