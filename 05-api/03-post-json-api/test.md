curl -X POST http://127.0.0.1:9501/api/echo \ 
  -H "Content-Type: application/json" \
  -d '{"name":"Klaus"}' | jq
