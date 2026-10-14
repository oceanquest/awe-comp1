#!/usr/bin/env bash
set -euo pipefail

URL="${1:-http://127.0.0.1:8080/products.json}"
ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
TMP_DIR="$ROOT_DIR/.tmp"
mkdir -p "$TMP_DIR"
json_response_file="$(mktemp "$TMP_DIR/json-response.XXXXXX")"

cleanup() {
  status=$?
  if [ "$status" -eq 0 ]; then
    rm -f "$json_response_file"
    echo "Temporary JSON response removed."
  else
    echo "Validation failed. Response saved to $json_response_file"
  fi

  if [ -n "${SERVER_PID:-}" ]; then
    kill "$SERVER_PID" 2>/dev/null || true
  fi
}

trap cleanup EXIT

if ! curl -fsSL "$URL" >/dev/null 2>&1; then
  echo "Starting local PHP server for JSON validation"
  php -S 127.0.0.1:8080 -t "$ROOT_DIR" >/tmp/awe.log 2>&1 &
  SERVER_PID=$!

  for _ in {1..20}; do
    if curl -fsSL "$URL" >/dev/null 2>&1; then
      break
    fi
    sleep 0.5
  done
fi

if command -v jq >/dev/null 2>&1; then
  echo "Fetching JSON from $URL"
  curl -fsSL "$URL" > "$json_response_file"
  echo "== JSON response =="
  cat "$json_response_file"
  echo "--- validating ---"
  jq empty "$json_response_file"
  echo "JSON passed jq validation"
else
  echo "jq not installed; using Python JSON parser instead"
  curl -fsSL "$URL" > "$json_response_file"
  echo "== JSON response =="
  cat "$json_response_file"
  echo "--- validating ---"
  python3 -c 'import json, sys; json.load(open(sys.argv[1])); print("JSON passed Python validation")' "$json_response_file"
fi
