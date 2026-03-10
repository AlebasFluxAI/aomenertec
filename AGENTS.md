# AGENTS.md

Guide for AI coding agents working on the Enertec Laravel project.

## 🚀 Project Overview

Laravel 8.75 + Livewire 2.5 + Jetstream energy management system with MQTT IoT device communication. Dockerized with Laravel Sail. Uses versioned architecture (V1 namespace).

**Stack**: PHP 8.1, PostgreSQL 14, Redis, Mosquitto MQTT, Livewire, Tailwind CSS 3.0, Alpine.js 3.0, Socket.io

## 🐳 Environment Setup

### Development (Local)
```bash
# First time setup
make setup                    # Full automated setup (creates shared network automatically)

# Daily workflow
make up                       # Start services
make down                     # Stop services
make watch                    # Hot reload assets

# Common tasks
make migrate                  # Run migrations
make cache-clear              # Clear all caches
make test                     # Run all tests
```

> **Shared Docker Network**: All services join the `enertec-shared` external Docker network, allowing `aomenertec-api` containers to reach PostgreSQL, Redis, and Mosquitto by container name. `make setup` / `make prod-deploy` create the network automatically. To create it manually: `make network`.

### Production (Ubuntu Server)
```bash
# First time deployment (fresh install with seeders)
make prod-deploy-fresh        # Full deployment + migrate:fresh --seed
make prod-mqtt-password       # Configure MQTT (interactive, REQUIRED)

# Normal deployment (existing database)
make prod-deploy              # Full deployment with migrations only
make prod-mqtt-password       # Configure MQTT (interactive, REQUIRED)

# After git pull
make prod-update              # Update code, assets, migrations

# Maintenance
make prod-restart             # Restart services
make prod-logs                # View logs
make prod-ps                  # Container status
make prod-seed                # Run seeders only
make prod-create-db           # Create database if not exists
```

> ⚠️ **IMPORTANT**: After `prod-deploy` or `prod-deploy-fresh`, you MUST run `make prod-mqtt-password` to configure MQTT authentication. Enter the same password as `MQTT_AUTH_PASSWORD` in `.env.production`.

> 📖 See [DEPLOYMENT-PRODUCTION.md](DEPLOYMENT-PRODUCTION.md) for full production guide.

**Access URLs**:
- App: http://localhost
- Echo Server (WebSockets): https://localhost:8443
- PostgreSQL: localhost:5432 (user: sail, pass: password, db: enertec)
- Redis: localhost:6379
- MQTT Broker: localhost:1883 (user: enertec, pass: enertec2020**)

## 🧪 Testing Commands

```bash
# Run all tests
./vendor/bin/sail artisan test
# OR
make test

# Run specific test file
./vendor/bin/sail artisan test --filter=AuthenticationTest

# Run specific test method
./vendor/bin/sail artisan test --filter=test_login_screen_can_be_rendered

# Run with coverage
./vendor/bin/sail artisan test --coverage
# OR
make test-coverage

# Run only Unit tests
./vendor/bin/sail artisan test --testsuite=Unit

# Run only Feature tests
./vendor/bin/sail artisan test --testsuite=Feature

# Legacy PHPUnit (avoid if possible)
./vendor/bin/sail exec laravel.test vendor/bin/phpunit
./vendor/bin/sail exec laravel.test vendor/bin/phpunit --filter=AuthenticationTest
```

## 📁 Architecture & File Structure

**Versioned V1 namespace** - ALL business logic lives in V1:
- Models: `app/Models/V1/`
- Controllers: `app/Http/Controllers/V1/{Domain}/`
- Livewire: `app/Http/Livewire/V1/{Domain}/{Action}{Entity}.php`
- Commands: `app/Console/Commands/V1/`
- Routes: `routes/V1/` (api.php, web.php, channels.php, console.php)

**Domains**: Admin, Client, NetworkOperator, Technician, MqttInput, EventLog, ConfigurationClient

## 🎨 Code Style Guidelines

### PHP Standards

**Indentation**: 4 spaces (see .editorconfig)
**Line endings**: LF
**Final newline**: Required
**Trailing whitespace**: Remove (except Markdown)

**Naming Conventions**:
- Models: `PascalCase` (e.g., `Admin`, `Client`, `MicrocontrollerData`)
- Controllers: `PascalCase` + `Controller` suffix (e.g., `AuthController`)
- Livewire: `PascalCase` + Action prefix (e.g., `AddAlertType`, `EditClient`)
- Methods: `camelCase` (e.g., `submitForm`, `getCurrentEnabledClients`)
- Variables: `camelCase` or `snake_case` for DB columns (e.g., `$clientType`, `$network_operator_id`)
- Database fields: `snake_case` (e.g., `user_id`, `created_at`, `network_operator_id`)
- Routes: `kebab-case` or `dot.notation` (e.g., `v1.admin.client.list.client`)

### Imports Order

```php
<?php

namespace App\Models\V1;

// 1. Laravel core
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// 2. Third-party packages
use Spatie\Permission\Traits\HasPermissions;

// 3. App imports
use App\Models\Traits\AuditableTrait;
use App\Models\Traits\ImageableTrait;
use App\Scope\OrderIdScope;

class Admin extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasPermissions;
    use AuditableTrait;
    use ImageableTrait;
    
    // ...
}
```

### Controller Patterns

```php
<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * DocBlock for every public method
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        
        if (!$token = auth("api")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $this->respondWithToken($token);
    }
}
```

### Livewire Component Pattern

```php
<?php

namespace App\Http\Livewire\V1\Admin\AlertType;

use App\Http\Services\V1\Admin\AlertType\AlertTypeAddService;
use Livewire\Component;

class AddAlertType extends Component
{
    // Public properties for binding
    public $name;
    public $value;
    public $unit;

    private $addEquipmentAlertTypeService;

    public function __construct($id = null)
    {
        $this->addEquipmentAlertTypeService = AlertTypeAddService::getInstance();
        parent::__construct($id);
    }

    public function mount()
    {
        $this->addEquipmentAlertTypeService->mount($this);
    }

    public function submitForm()
    {
        $this->addEquipmentAlertTypeService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.administrar.v1.alertType.add-alert-type')
            ->extends('layouts.v1.app');
    }
}
```

### Model Patterns

- Use **Traits** extensively: `AuditableTrait`, `ImageableTrait`, `PaginatorTrait`, `PermissionTrait`
- Always define `$fillable` array
- Use **SoftDeletes** for logical deletion
- Use **Global Scopes** when needed (e.g., `OrderIdScope`)
- Use **Accessors** for computed properties (e.g., `getPhonePlusIndicativeAttribute`)
- Use **match expressions** (PHP 8+) for mappings

### Error Handling

```php
// Return JSON with appropriate HTTP status codes
return response()->json([
    'success' => false,
    'message' => 'Client not found'
], 404);

// Validate before processing
if ($orderModel->status == WorkOrder::WORK_ORDER_STATUS_CLOSED) {
    return response()->json([
        'success' => false,
        'message' => 'The order is already closed.'
    ], 409); // HTTP 409 Conflict
}

// Validate file uploads with size limits
if ($image_file->getSize() > 6 * 1024 * 1024) { // 6MB
    return response()->json([
        'success' => false,
        'message' => 'The image exceeds the maximum size of 6MB.'
    ], 413); // HTTP 413 Payload Too Large
}
```

### Database Queries

```php
// Use relationships
$clients = Client::whereIn('network_operator_id', $this->networkOperators()->pluck('id'))
    ->orWhere("admin_id", $this->id);

// Eager loading
$this->equipments()->with("equipmentType")->get();

// Key-value arrays for dropdowns
return (array_merge(
    [["key" => "Seleccione el tipo de equipo ...", "value" => null]],
    ($this->equipments()->get()->map(function ($equipment) {
        return [
            "key" => $equipment->id . "- " . $equipment->serial,
            "value" => $equipment->id,
        ];
    }))->toArray()
));
```

## 🔑 Key Conventions

1. **Use V1 namespace** for all new code
2. **Service Layer Pattern**: Controllers delegate to Services (e.g., `AlertTypeAddService`)
3. **Singleton Services**: Use `::getInstance()` for service classes
4. **Broadcasting**: Use Laravel Echo + Redis for real-time updates
5. **MQTT Processing**: PHP-MQTT `ConsumerCommand` (`php artisan mqtt:consume`) subscribes directly to Mosquitto topics and dispatches jobs. Legacy Python scripts have been removed.
6. **Authentication**: JWT for API, Jetstream for web
7. **Permissions**: Spatie Laravel Permission package
8. **Constants**: Define status constants on models (e.g., `WorkOrder::WORK_ORDER_STATUS_OPEN`)
9. **API URL config**: Use `config('aom.api_url')`, `config('aom.api_config_path')`, `config('aom.api_clients_path')` from `config/aom.php` — NEVER use `env()` directly for API URLs in service code

## 🚨 Important Notes

- **NEVER** commit without explicit user request
- **NEVER** run destructive commands without confirmation
- **NEVER** modify database data directly (Tinker, raw SQL, etc.). **ALL database changes MUST go through migrations or seeders** so they are reproducible and deployable to production
- **NEVER** copy files directly to the production server via SCP or any other method that bypasses git. ALL changes to production MUST go through: local change → commit → push → `git pull` on server. No exceptions.
- **ALWAYS** use `./vendor/bin/sail` prefix in Docker environment
- **ALWAYS** clear cache after config changes: `make cache-clear`
- **CHECK** if MQTT/Echo Server processes are running via Supervisor: `make supervisor-status`
- **DEFAULT PASSWORD**: User identification number (see UserObserver)
- **TEST CREDENTIALS**: sadminprueba@fluxai.local / 111111111
- **API Key (inter-service)**: `dev-api-key-enertec-2026` (header: `x-api-key`) — created by `ApiKeySeeder`

## 📊 Critical Business Logic

- **Data Processing**: Cron jobs run every 2 minutes to process MQTT data
- **Averages**: Hourly/Daily/Monthly consumption calculated by scheduled commands
- **Invoicing**: Auto-generated monthly on day 1
- **Alerts**: Configurable per-client alert system
- **Broadcasting**: Real-time data pushed via Laravel Echo Server (port 8443)

## 🛠️ Useful Commands

```bash
# Artisan commands
./vendor/bin/sail artisan mqtt:consume                      # Start MQTT consumer (subscribes to IoT topics)
./vendor/bin/sail artisan schedule:run                      # Run scheduled tasks manually
./vendor/bin/sail artisan update:data-consumption           # Process consumption data
./vendor/bin/sail artisan average:hourly-consumption        # Calculate hourly averages
./vendor/bin/sail artisan client:invoice-generation         # Generate invoices

# Debugging
make logs-app                                               # Laravel logs
make logs-mqtt                                              # MQTT broker logs
make supervisor-status                                      # Check background processes
make shell                                                  # Enter container shell
make tinker                                                 # Laravel Tinker REPL

# Database
make migrate-fresh                                          # Reset DB (⚠️ destroys data)
make migrate-seed                                           # Reset DB + seeders
make db-shell                                               # PostgreSQL CLI

# Docker networking
make network                                                # Create enertec-shared network

# Assets
make watch                                                  # Hot reload CSS/JS
make prod                                                   # Production build
```

## 🔍 Before Making Changes

1. **Read existing code** in the same domain for patterns
2. **Check if Service class exists** for the controller
3. **Verify middleware** requirements in routes
4. **Clear caches** after config/route changes
5. **Test MQTT flow** if touching MqttInput controllers
6. **Verify Supervisor** processes if changing MQTT consumer or queue workers
7. **Check permissions** in `/config/permissions.php`

## 📝 When Writing Tests

- Place in `tests/Feature/` for HTTP tests
- Place in `tests/Unit/` for isolated unit tests
- Use `RefreshDatabase` trait for DB tests
- Follow naming: `test_users_can_authenticate_using_the_login_screen`
- Test V1 routes: `$this->get('v1/login')`

---

**For complete documentation**:
- CLAUDE.md - Technical documentation
- README-DOCKER.md - Docker development guide
- DEPLOYMENT-PRODUCTION.md - Production deployment guide
- Makefile - Run `make help` for all commands
