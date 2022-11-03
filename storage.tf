# resource "digitalocean_spaces_bucket" "storage-bucket" {
#   name   = "${local.prefix}bucket"
#   region = "fra1"

#   cors_rule {
#     allowed_headers = ["*"]
#     allowed_methods = ["GET"]
#     allowed_origins = ["*"]
#     max_age_seconds = 3000
#   }

#   cors_rule {
#     allowed_headers = ["*"]
#     allowed_methods = ["PUT", "POST", "DELETE"]
#     allowed_origins = ["https://ashdavies.online"]
#     max_age_seconds = 3000
#   }
# }
