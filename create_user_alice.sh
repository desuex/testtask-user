#!/usr/bin/env bash
curl -X POST -H "Content-Type: application/json" -d '{
  "name": "alicesmith",
  "email": "alice@example.com",
  "created": "2023-06-30"
}' http://localhost:8080/users
