resource "aws_alb" "lb" {
  name               = "${local.prefix}lb"
  internal           = false
  load_balancer_type = "application"
  security_groups    = [aws_security_group.sg.id]

  subnets = [for subnet in aws_subnet.public : subnet.id]
}

resource "aws_alb_target_group" "tg" {
  name        = "${local.prefix}lb-tg"
  port        = 80
  protocol    = "HTTP"
  vpc_id      = aws_vpc.vpc.id
  target_type = "ip"

  # health_check {
  #   healthy_threshold   = "3"
  #   interval            = "30"
  #   protocol            = "HTTP"
  #   matcher             = "200"
  #   timeout             = "3"
  #   path                = "/"
  #   unhealthy_threshold = "2"
  # }
}

resource "aws_alb_listener" "service" {
  load_balancer_arn = aws_alb.lb.arn
  port              = 80
  protocol          = "HTTP"

  default_action {
    type             = "forward"
    target_group_arn = aws_alb_target_group.tg.arn
  }
}