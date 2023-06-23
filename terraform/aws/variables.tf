variable "mysql_pwd" {
  type        = string
  description = "Password to use for the primary MySQL user"
  sensitive   = true
}
variable "mysql_user" {
  type        = string
  description = "Username to use for the primary MySQL user"
  sensitive   = true
}
variable "mysql_version" {
  type        = string
  description = "Version of MySQL to deploy"
  default     = "5.7"
}
variable "redis_version" {
  type        = string
  description = "Version of Redis to deploy"
  default     = "7"
}
variable "container_count" {
  type        = number
  description = "Number of Containers to spin up"
  default     = 3
}
variable "keypair" {
  type        = string
  description = "Name of the AWS Key Pair to add to any EC2 instances"
}
variable "ip_addr" {
  type        = string
  description = "My IP Address"
}