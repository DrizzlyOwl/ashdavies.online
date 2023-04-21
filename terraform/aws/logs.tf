resource "aws_cloudwatch_log_group" "cw" {
  name              = "/ecs/cb-app"
  retention_in_days = 7
}

resource "aws_cloudwatch_log_stream" "stream" {
  name           = "log-stream"
  log_group_name = aws_cloudwatch_log_group.cw.name
}