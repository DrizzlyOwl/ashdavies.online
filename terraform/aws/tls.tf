resource "aws_lightsail_certificate" "tls" {
  name                      = "${local.prefix}tls"
  domain_name               = local.domain
  subject_alternative_names = ["www.${local.domain}"]
}
