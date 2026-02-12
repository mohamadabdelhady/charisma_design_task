# Architectural Decisions

## Framework & Infrastructure
- **Laravel 11**: RESTful API-first architecture (no views needed)
- **PostgreSQL**: Relational database with JSONB support for extensibility
- **Redis**: Caching layer with tag-based invalidation for performance
- **Docker Compose**: Containerized environment (app + postgres + redis) for consistency

## Data Model & Identity
- **UUID Primary Keys**: Globally unique identifiers instead of sequential IDs for security and distributed system readiness
- **Soft Deletes**: Logical deletion preserving data integrity and historical reference
- **Eloquent ORM**: Object-relational mapping for clean, expressive database interactions

## API Design
- **RESTful Resource Routing**: Standard HTTP verbs (GET, POST, PUT, DELETE) for resource operations
- **Rate Limiting**: Throttle middleware (60 requests/min) prevents abuse
- **Pagination Support**: Configurable per-page results (default 10) with caching
- **Validation-First**: Request validation integrated at controller level

## Stock Management & Events
- **Event-Driven Architecture**: `StockBelowThreshold` event dispatches when stock falls below threshold
- **Listener Pattern**: `SendStockAlert` listener responds to events (logs warnings)
- **Decoupling**: Events separate business logic from side effects, enabling easy listener addition (email, SMS, webhook)

## Performance & Caching
- **Tagged Cache**: Cache invalidation by domain (products) rather than keys
- **Cache-Aside Pattern**: Database queries cached for 60 seconds, invalidated on mutations
- Avoids N+1 queries through strategic caching

## Testing & Quality
- **Pest PHP**: Modern testing framework for feature and unit tests
- **Test Coverage**: Comprehensive feature tests for all CRUD operations and stock management
