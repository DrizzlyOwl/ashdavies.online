resource "aws_ses_domain_identity" "mail" {
  domain = local.domain
}

resource "aws_ses_domain_dkim" "mail" {
  domain = aws_ses_domain_identity.mail.domain
}

resource "aws_ses_domain_mail_from" "mail" {
  domain           = aws_ses_domain_identity.mail.domain
  mail_from_domain = "bounce.${aws_ses_domain_identity.mail.domain}"
}

resource "aws_ses_configuration_set" "mail" {
  name = "${local.project_name}-ses-config"

  delivery_options {
    tls_policy = "Require"
  }
}

resource "aws_ses_domain_identity_verification" "mail" {
  domain = aws_ses_domain_identity.mail.id

  depends_on = [aws_route53_record.ses]
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
