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
