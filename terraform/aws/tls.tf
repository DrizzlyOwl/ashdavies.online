resource "aws_alb_listener_certificate" "tls" {
  listener_arn    = aws_alb_listener.https.arn
  certificate_arn = aws_acm_certificate.tls.arn
}

resource "aws_acm_certificate" "tls" {
  domain_name       = local.domain
  validation_method = "DNS"
  key_algorithm     = "RSA_2048"
  subject_alternative_names = [
    "*.${local.domain}"
  ]

  lifecycle {
    create_before_destroy = true
  }
}

resource "aws_acm_certificate_validation" "tls" {
  certificate_arn         = aws_acm_certificate.tls.arn
  validation_record_fqdns = [for record in aws_route53_record.tls : record.fqdn]
}

resource "aws_route53_record" "tls" {
  for_each = {
    for dvo in aws_acm_certificate.tls.domain_validation_options : dvo.domain_name => {
      name   = dvo.resource_record_name
      record = dvo.resource_record_value
      type   = dvo.resource_record_type
    }
  }

  allow_overwrite = true
  name            = each.value.name
  records         = [each.value.record]
  ttl             = 60
  type            = each.value.type
  zone_id         = aws_route53_zone.domain.zone_id
}