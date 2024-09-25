output "availability_zone" {
  value = data.aws_availability_zones.az.names[0]
}

output "dns_ns_records" {
  value = aws_route53_zone.domain.name_servers
}

output "container_image" {
  value = "${aws_ecr_repository.ecr.repository_url}:${local.image.tag}"
}
