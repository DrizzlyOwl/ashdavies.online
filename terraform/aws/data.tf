data "aws_availability_zones" "az" {
  state = "available"

  filter {
    name   = "region-name"
    values = [local.region]
  }
}
data "aws_caller_identity" "current" {}
