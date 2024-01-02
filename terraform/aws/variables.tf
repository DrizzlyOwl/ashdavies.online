variable "mysql_name" {
  type        = string
  description = "Name to use for the primary MySQL database"
  sensitive   = true
}
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
  default     = "8_0"
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
variable "ip_addr" {
  type        = string
  description = "My IP Address"
}