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

resource "aws_iam_user" "mail" {
  name = "${local.project_name}-ses-user"
}

data "aws_iam_policy_document" "mail" {
  statement {
    effect    = "Allow"
    actions   = ["ses:*"]
    resources = ["*"]
  }
}

resource "aws_iam_user_policy" "mail" {
  name   = "${local.project_name}-ses-policy"
  user   = aws_iam_user.mail.name
  policy = data.aws_iam_policy_document.mail.json
}

resource "aws_iam_user" "deployment" {
  name = "${local.project_name}-github-actions"
}

data "aws_iam_policy_document" "ecr_repo_policy" {
  statement {
    effect = "Allow"
    sid    = "Allow push only from github actions"

    principals {
      type        = "AWS"
      identifiers = ["arn:aws:iam::${local.account_id}:role/github-actions"]
    }

    actions = [
      "ecr:BatchCheckLayerAvailability",
      "ecr:CompleteLayerUpload",
      "ecr:InitiateLayerUpload",
      "ecr:PutImage",
      "ecr:UploadLayerPart"
    ]

    condition {
      test     = "StringEquals"
      variable = "aws:PrincipalAccount"
      values   = [aws_iam_user.deployment.id]
    }
  }
}
