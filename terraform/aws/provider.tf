terraform {
  required_version = ">=1.5.0"
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = ">= 5.5"
    }
  }
}

# Configure the AWS Provider
provider "aws" {
  region                   = "eu-west-2"
  shared_credentials_files = ["/Users/ash/.aws/credentials"]
  profile                  = "dxw-ash"
}
