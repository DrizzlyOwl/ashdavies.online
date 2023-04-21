terraform {
  required_version = "1.4.5"
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 4.0"
    }
  }
}

# Configure the AWS Provider
provider "aws" {
  region                   = "eu-west-2"
  shared_credentials_files = ["/Users/ash/.aws/credentials"]
  profile                  = "dxw-ash"
}
