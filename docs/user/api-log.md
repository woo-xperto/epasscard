# API log

**EpassCard → API Log** records outbound API requests for debugging.

## What is logged

Each entry includes:

- Date/time
- HTTP method and endpoint
- Success or failure
- Request and response bodies (JSON)
- Context label (e.g. `memberpress:create_pass`)

Logging occurs for template fetch, pass create/update, push notifications, and other API client calls.

## Viewing entries

- Browse paginated results (25 per page).
- Search by endpoint, context, or body content.
- Filter by success/failure.
- Expand a row to see full request/response JSON.

## Retention

Logs older than the retention period (default **30 days**) are purged automatically by daily cron. Retention is defined in `EPC_Api_Log` and may be filterable in future versions.

## When to use

- Pass creation fails with a generic error
- Field mapping looks correct but API rejects payload
- Verifying test push requests
- Sharing diagnostics with support (export/copy JSON from details)

## Privacy

Logs may contain member data sent to the API. Restrict admin access and clear logs on staging sites after testing.
