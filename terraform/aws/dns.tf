resource "aws_route53_zone" "domain" {
  name = local.domain
}

resource "aws_route53_record" "www" {
  zone_id = aws_route53_zone.domain.zone_id
  name    = local.domain
  type    = "A"

  alias {
    name                   = aws_alb.lb.dns_name
    zone_id                = aws_alb.lb.zone_id
    evaluate_target_health = true
  }
}