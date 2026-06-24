curl -X POST http://127.0.0.1:9501/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Klaus"}' | jq

curl -X POST http://127.0.0.1:9501/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Bob"}' | jq