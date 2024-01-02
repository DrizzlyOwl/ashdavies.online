resource "aws_route53_zone" "domain" {
  name = local.domain
}

resource "aws_route53_record" "www" {
  zone_id = aws_route53_zone.domain.zone_id
  name    = local.domain
  type    = "A"

  alias {
    name                   = replace(replace(aws_lightsail_container_service.container.url, "https:", ""), "/", "")
    zone_id                = "Z0624918ZXDYQZLOXA66"
    evaluate_target_health = true
  }
}

resource "aws_route53_record" "ses" {
  zone_id = aws_route53_zone.domain.zone_id
  name    = "_amazonses.${aws_ses_domain_identity.mail.id}"
  type    = "TXT"
  ttl     = "600"
  records = [aws_ses_domain_identity.mail.verification_token]
}

resource "aws_route53_record" "dkim" {
  count   = 3
  zone_id = aws_route53_zone.domain.zone_id
  name    = "${aws_ses_domain_dkim.mail.dkim_tokens[count.index]}._domainkey"
  type    = "CNAME"
  ttl     = "600"
  records = ["${aws_ses_domain_dkim.mail.dkim_tokens[count.index]}.dkim.amazonses.com"]
}

resource "aws_route53_record" "mx" {
  zone_id = aws_route53_zone.domain.zone_id
  name    = aws_ses_domain_mail_from.mail.mail_from_domain
  type    = "MX"
  ttl     = "600"
  records = ["10 feedback-smtp.${local.region}.amazonses.com"]
}

resource "aws_route53_record" "sfp" {
  zone_id = aws_route53_zone.domain.zone_id
  name    = aws_ses_domain_mail_from.mail.mail_from_domain
  type    = "TXT"
  ttl     = "600"
  records = ["v=spf1 include:amazonses.com -all"]
}
