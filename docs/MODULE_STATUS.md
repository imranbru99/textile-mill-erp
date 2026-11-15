# Module status

| Module | Data | Filament | Service | Demo path | Tests |
|---|---|---|---|---|---|
| Foundation | yes | yes | approvals, numbers, audit, modules | company, roles, series | install |
| Master | yes | yes | count conversion | 200+ items, machines, UOM | formula |
| Commercial | yes | yes | order flow | inquiry, quotation, sales order | install |
| Supply | yes | yes | order flow | PR, PO, GRN quality hold | install |
| Inventory | yes | yes | stock, valuation | bale and dye lots | install |
| Gate | yes | yes | order flow | gate pass | — |
| Logistics | yes | yes | order flow | shipment | — |
| Spinning | yes | yes | ring frame, mixing | mixing, yarn lot, floor page | formula |
| Weaving | yes | yes | meters, 4-point | roll grade | formula |
| Knitting | yes | yes | schema | toggleable, no demo batch | — |
| Dyeing | yes | yes | recipe scale | lab dip, batch, finishing | formula |
| Planning | yes | yes | order flow | plan, BOM, MRP | — |
| Quality | yes | yes | AQL | inspection accept | formula |
| Costing | yes | yes | cost sheet | quote rate | formula |
| Maintenance | yes | yes | schema + issue rules | PM rule seeded | — |
| Utilities | yes | yes | issue rules | spike rule seeded | — |
| People | yes | yes | overtime | 50 workers, payroll line | formula |
| Finance | yes | yes | posting | invoice and receipt journals | install |
| Compliance | yes | yes | bonded balance | rule seeded | formula |
| Projects | yes | yes | schema | — | — |
| Issues | yes | yes | engine + studio | cotton hold, waste | install |
| Website | yes | yes | public site | home, products, track | install |
| Reports | yes | yes | daily MIS job | scheduled 07:00 | — |

`php artisan mill:install --fresh` loads Padma Textile and one export order from inquiry to realization.
