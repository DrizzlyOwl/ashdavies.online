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

    actions = [
      "ecr:BatchCheckLayerAvailability",
      "ecr:CompleteLayerUpload",
      "ecr:InitiateLayerUpload",
      "ecr:PutImage",
      "ecr:UploadLayerPart"
    ]
    resources = [aws_ecr_repository.ecr.arn]
  }
}

resource "aws_iam_user_policy" "deployment" {
  name   = "${local.project_name}-github-action"
  user   = aws_iam_user.deployment.name
  policy = data.aws_iam_policy_document.ecr_repo_policy.json
}

data "aws_iam_policy_document" "assume_role" {
  statement {
    effect = "Allow"

    principals {
      type        = "AWS"
      identifiers = [aws_iam_user.deployment.arn]
    }

    actions = ["sts:AssumeRole"]
  }
}

resource "aws_iam_role" "github_action" {
  name               = "${local.project_name}-github-action"
  assume_role_policy = data.aws_iam_policy_document.assume_role.json
}

resource "aws_iam_role_policy" "ecs_task_execution_role_policy" {
  name   = "${terraform.workspace}_ete_role_policy"
  policy = data.aws_iam_policy_document.ecr_repo_policy.json
  role   = aws_iam_role.github_action.id
}
