resource "aws_route53domains_registered_domain" "default" {
  domain_name = local.domain

  dynamic "name_server" {
    for_each = aws_route53_zone.domain.name_servers

    content {
      name = name_server.value
    }
  }
}
