resource "aws_lightsail_container_service" "container" {
  name        = "${local.prefix}lightsail"
  power       = "nano"
  scale       = local.instance_count
  is_disabled = false

  public_domain_names {
    certificate {
      certificate_name = aws_lightsail_certificate.tls.name
      domain_names     = [local.domain]
    }
  }

  private_registry_access {
    ecr_image_puller_role {
      is_active = true
    }
  }
}

resource "aws_ecr_repository_policy" "default" {
  repository = aws_ecr_repository.ecr.name
  policy     = data.aws_iam_policy_document.imagepull.json
}

resource "aws_lightsail_container_service_deployment_version" "container" {
  container {
    container_name = "${local.prefix}web"
    image          = "${aws_ecr_repository.ecr.repository_url}:${local.image.tag}"

    command = []

    environment = local.container_env

    ports = {
      80 = "HTTP"
    }
  }


  dynamic "container" {
    for_each = local.wordpress.enable_redis ? [1] : []

    content {
      container_name = "${local.prefix}redis"
      image          = "redis:latest"

      command = []

      ports = {
        6379 = "TCP"
      }
    }
  }

  public_endpoint {
    container_name = "${local.prefix}web"
    container_port = 80

    health_check {
      healthy_threshold   = 2
      unhealthy_threshold = 2
      timeout_seconds     = 2
      interval_seconds    = 5
      path                = "/health.txt"
      success_codes       = "200"
    }
  }

  service_name = aws_lightsail_container_service.container.name
}
