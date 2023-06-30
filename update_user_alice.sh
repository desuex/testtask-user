#!/usr/bin/env bash
curl -X PUT -H "Content-Type: application/json" -d '{
  "id": 1,
  "name": "Alice",
  "email": "alice@example.com",
  "created": "2023-06-30",
  "deleted": null,
  "notes": null
}' http://localhost:8080/users/1
