#!/bin/sh

curl \
  -X PUT \
  -d '{"user":{"variables_order":"EGPCS"}}' \
  --unix-socket /var/run/control.unit.sock \
  http://localhost/config/applications/drupal/options
