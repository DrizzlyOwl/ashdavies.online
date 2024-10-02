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
