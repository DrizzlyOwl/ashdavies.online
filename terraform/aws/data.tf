data "aws_availability_zones" "az" {
  state = "available"

  filter {
    name   = "region-name"
    values = [local.region]
  }
}
data "aws_caller_identity" "current" {}

data "aws_iam_policy_document" "github" {
  statement {
    effect  = "Allow"
    actions = ["sts:AssumeRoleWithWebIdentity"]
    principals {
      type        = "Federated"
      identifiers = [aws_iam_openid_connect_provider.github.arn]
    }
    condition {
      test     = "StringEquals"
      variable = "token.actions.githubusercontent.com:aud"
      values   = ["sts.amazonaws.com"]
    }
    condition {
      test     = "StringEquals"
      variable = "token.actions.githubusercontent.com:sub"
      values   = ["repo:${local.github.repo}:ref:refs/heads/${local.github.branch}"]
    }
  }
}

data "aws_iam_policy_document" "imagepush" {
  statement {
    effect = "Allow"
    actions = [
      "ecr:BatchGetImage",
      "ecr:GetDownloadUrlForLayer",
      "ecr:BatchCheckLayerAvailability",
      "ecr:CompleteLayerUpload",
      "ecr:InitiateLayerUpload",
      "ecr:PutImage",
      "ecr:UploadLayerPart"
    ]
    resources = [aws_ecr_repository.ecr.arn]
  }

  statement {
    actions   = ["ecr:GetAuthorizationToken"]
    resources = ["*"]
  }
}

data "aws_iam_policy_document" "lightsail" {
  statement {
    effect = "Allow"
    actions = [
      "lightsail:CreateContainerServiceDeployment",
      "lightsail:GetContainerServiceDeployments"
    ]
    resources = [aws_lightsail_container_service.container.arn]
  }
}

data "aws_iam_policy_document" "mail" {
  statement {
    effect    = "Allow"
    actions   = ["ses:*"]
    resources = ["*"]
  }
}

data "aws_iam_policy_document" "imagepull" {
  statement {
    effect = "Allow"

    principals {
      type = "AWS"
      identifiers = [
        aws_lightsail_container_service.container.private_registry_access[0].ecr_image_puller_role[0].principal_arn
      ]
    }

    actions = [
      "ecr:BatchGetImage",
      "ecr:GetDownloadUrlForLayer",
    ]
  }
}
