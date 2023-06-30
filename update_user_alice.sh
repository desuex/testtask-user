#!/usr/bin/env bash
curl -X PUT -H "Content-Type: application/json" -d '{
  "name": "AliceSmith123",
  "email": "alice123@example.com",
  "created": "2023-06-30",
}' http://localhost:8080/users/1
