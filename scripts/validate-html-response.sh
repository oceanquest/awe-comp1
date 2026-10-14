#!/usr/bin/env bash
# Local equivalent of the .github/workflows/classroom.yml test steps.
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
TMP_DIR="$ROOT_DIR/.tmp"
mkdir -p "$TMP_DIR"
HTML_RESPONSE_FILE="$TMP_DIR/html-response.html"

cleanup() {
  status=$?
  if [ "$status" -eq 0 ]; then
    rm -f "$HTML_RESPONSE_FILE"
    echo "Temporary HTML response removed."
  else
    echo "Validation failed. Response saved to $HTML_RESPONSE_FILE"
  fi

  if [ -n "${SERVER_PID:-}" ]; then
    kill "$SERVER_PID" 2>/dev/null || true
  fi
}

trap cleanup EXIT

chmod 777 "$ROOT_DIR/products.json"

php -S 127.0.0.1:8080 -t "$ROOT_DIR" >/tmp/awe.log 2>&1 &
SERVER_PID=$!

# Give the server a moment to start.
for _ in {1..10}; do
  curl -sSf http://127.0.0.1:8080 >/dev/null 2>&1 && break
  sleep 0.5
done

echo "== Test Post Request =="
curl -sS -H 'Content-Type: application/x-www-form-urlencoded' \
  -d "producttype=cd&fname=Ed&sname=Sheeran&title=Wildflowers&price=200&pages=0" \
  -X POST \
  http://127.0.0.1:8080 >/dev/null

echo "== Test site and lint returned HTML =="
curl -sSf http://127.0.0.1:8080 > "$HTML_RESPONSE_FILE"
cat "$HTML_RESPONSE_FILE"
echo "--- validating ---"
npx html-validate "$HTML_RESPONSE_FILE"
