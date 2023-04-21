terraform {
  backend "s3" {
    bucket                  = "ashdavies-tf"
    key                     = "terraform.tfstate"
    region                  = "eu-west-2"
    encrypt                 = "true"
    profile                 = "dxw-ash"
    shared_credentials_file = "/Users/ash/.aws/credentials"
  }
}