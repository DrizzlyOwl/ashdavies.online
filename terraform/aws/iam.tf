resource "aws_iam_user" "mail" {
  name = "${local.project_name}-ses-user"
}

resource "aws_iam_user_policy" "mail" {
  name   = "${local.project_name}-ses-policy"
  user   = aws_iam_user.mail.name
  policy = data.aws_iam_policy_document.mail.json
}

// Register GitHub as an OIDC Provider
resource "aws_iam_openid_connect_provider" "github" {
  url             = "https://token.actions.githubusercontent.com"
  client_id_list  = ["sts.amazonaws.com"]
  thumbprint_list = ["6938fd4d98bab03faadb97b34396831e3780aea1", "1c58a3a8518e8759bf075b76b750d4f2df264fcd"]

  tags = local.tags
}

// Create a specific Role for GitHub deployments
resource "aws_iam_role" "github" {
  name               = "${local.project_name}-github-role"
  assume_role_policy = data.aws_iam_policy_document.github.json
  tags               = local.tags
}

resource "aws_iam_policy" "github" {
  name        = "${local.project_name}-github-ecr-push"
  description = "Allow push only from GitHub actions"
  policy      = data.aws_iam_policy_document.imagepush.json
}

resource "aws_iam_role_policy_attachment" "github" {
  role       = aws_iam_role.github.name
  policy_arn = aws_iam_policy.github.arn
}

resource "aws_iam_policy" "lightsail" {
  name        = "${local.project_name}-github-lightsail-deploy"
  description = "Allow new service deployment on Lightsail from GitHub actions"
  policy      = data.aws_iam_policy_document.lightsail.json
}

resource "aws_iam_role_policy_attachment" "lightsail" {
  role       = aws_iam_role.github.name
  policy_arn = aws_iam_policy.lightsail.arn
}
