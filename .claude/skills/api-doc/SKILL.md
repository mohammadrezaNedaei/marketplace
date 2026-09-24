---
name: api-doc
description: Generate OpenAPI/Swagger documentation for API routes and endpoints
disable-model-invocation: true
---

# API Documentation Generator

Generate comprehensive OpenAPI 3.0 documentation for this Laravel marketplace.

## When to Use
- After adding new API endpoints
- Before launching public API
- When integrating with mobile apps
- For third-party seller integrations

## Workflow

### 1. Scan Routes
```bash
php artisan route:list --path=api --json
```

### 2. Document Each Endpoint
For each route, document:
- HTTP method and path
- Request parameters (path, query, body)
- Authentication requirements
- Response schemas
- Error responses
- Rate limits

### 3. Generate OpenAPI Spec
Create `docs/openapi.yaml` with proper structure for all marketplace endpoints.

### 4. Add Request/Response Examples
Document realistic examples for:
- Product listing with filters
- Order creation flow
- Wallet operations
- Error responses

### 5. Validate Spec
```bash
npx @redocly/cli lint docs/openapi.yaml
```

## Output
- `docs/openapi.yaml` — Main spec file
- `docs/examples/` — Request/response examples
- `docs/guides/` — Integration guides for sellers/buyers

## Files to Create
- [ ] `docs/openapi.yaml`
- [ ] `docs/examples/products.json`
- [ ] `docs/examples/orders.json`
- [ ] `docs/examples/wallet.json`
