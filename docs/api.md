# API

Base path `/api/v1`. Staff and floor tablets use a Sanctum token.

| Method | Path | Body |
|---|---|---|
| POST | `/api/v1/login` | `email`, `password`, optional `device` |
| GET | `/api/v1/orders` | Bearer token |
| GET | `/api/v1/stock` | Bearer token |
| GET | `/api/v1/issues` | Bearer token |
| POST | `/api/v1/floor/spinning` | `machine_id`, `rpm`, `tpi`, `count_ne`, `hours`, `efficiency`, `input_kg` |

The spinning endpoint stores a posted shift and returns kilograms from the ring-frame formula.
