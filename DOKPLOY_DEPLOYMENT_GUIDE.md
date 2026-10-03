# Deploying SOV-SUMMIT on Dokploy

This guide outlines how to deploy the **SOV-SUMMIT** Laravel application onto [Dokploy](https://dokploy.com) using Docker and Docker Compose.

---

## 🏗️ Architecture Overview

The production containerization includes:
- **Multi-Stage Dockerfile**:
  - **Node 22 (Alpine)**: Compiles modern frontend assets using Vite, TailwindCSS, and Alpine.js.
  - **PHP 8.3 FPM (Alpine)**: Lightweight, secure PHP runtime with all required extensions (`pdo_mysql`, `redis`, `gd`, `intl`, `bcmath`, `pcntl`, `opcache`, `zip`).
  - **Dompdf & Font Support**: Pre-installed `fontconfig` and `ttf-dejavu` TrueType fonts to ensure invoice PDF generation works reliably without font rendering errors.
  - **Nginx Web Server**: High-performance HTTP server with Gzip compression, fastcgi buffering, security headers, and static asset caching.
  - **Supervisord**: Process supervisor handling Nginx, PHP-FPM, and optional worker/scheduler processes.
- **Docker Compose Stack**:
  - `app`: Production Laravel web application (Port 80).
  - `queue`: Dedicated background worker for asynchronous jobs (`php artisan queue:work`).
  - `scheduler`: Automated cron runner for Laravel scheduled tasks (`php artisan schedule:work`).
  - `db`: MySQL 8.0 database with persistent volume and automatic healthchecks.
  - `redis`: Redis 7 Alpine for high-speed cache, session management, and queue queues.
- **Healthcheck**: Uses Laravel's built-in `/up` health check endpoint for zero-downtime rolling deploys.

---

## 🚀 Deployment Methods in Dokploy

You can deploy using either of two approaches in Dokploy:

### Method 1: Deploy as a Dokploy Compose Application (Recommended)

This method deploys the entire stack (Laravel, Queue Worker, Cron Scheduler, MySQL, and Redis) together using `docker-compose.yml`.

1. **Create Project in Dokploy**:
   - Go to your Dokploy Dashboard.
   - Click **Create Project** (or select an existing project).

2. **Add a Compose Service**:
   - Inside the project, click **Create Service** and select **Compose**.
   - Name your service (e.g., `sov-summit-stack`).

3. **Source Code Configuration**:
   - Select your Git provider (GitHub, GitLab, Bitbucket, or Git Repository).
   - Enter Repository: `Wali-LR/SOV-SUMMIT` (or your repo URL).
   - Enter Branch: `main` (or your target production branch).
   - Compose Path: `docker-compose.yml`.

4. **Environment Variables**:
   - Go to the **Environment** tab in Dokploy.
   - Copy the contents of `.env.docker.example`.
   - Update values (especially `APP_KEY`, `APP_URL`, `DB_PASSWORD`, `REDIS_PASSWORD`, and Mail settings).
   - Click **Save**.

5. **Expose Domain via Dokploy Traefik**:
   - Go to the **Domains** tab in your Dokploy Compose service.
   - Click **Add Domain**.
   - **Service Name**: Select `app`.
   - **Container Port**: `80`.
   - **Host**: Enter your domain (e.g., `summit.yourdomain.com`).
   - Enable **HTTPS / Let's Encrypt** (Automatic SSL).
   - Click **Create**.

6. **Deploy**:
   - Click the **Deploy** button.
   - Dokploy will build the image, start all services, wait for database readiness, run migrations, and route traffic to the container.

---

### Method 2: Deploy as a Dokploy Application (Single Container + Managed Database)

If you prefer using Dokploy's 1-Click Managed MySQL and Redis services:

1. **Create Database in Dokploy**:
   - In your Dokploy project, click **Create Service** -> **Database** -> **MySQL**.
   - Note the internal host name, database name, user, and password provided by Dokploy.
   - (Optional) Create a **Redis** service in Dokploy as well.

2. **Create Application Service**:
   - Click **Create Service** -> **Application**.
   - Set Build Type to **Dockerfile**.
   - Select your Git repository and branch.

3. **Configure Volumes**:
   - Under the **Volumes** tab, add a persistent volume:
     - **Host/Volume Name**: `sov_storage`
     - **Mount Path**: `/var/www/html/storage`
   - This ensures uploaded images, PDFs, and invoices persist across deploys.

4. **Environment Variables**:
   - Go to the **Environment** tab and configure:
     ```env
     APP_NAME="SOV Summit"
     APP_ENV=production
     APP_KEY=base64:HTjkPq6yHxQYXIpYCjsXOs6W/sT93moM87Y7n6yD9fU=
     APP_DEBUG=false
     APP_URL=https://summit.yourdomain.com
     
     DB_CONNECTION=mysql
     DB_HOST=your-dokploy-mysql-service-name
     DB_PORT=3306
     DB_DATABASE=sov_summit
     DB_USERNAME=your_mysql_user
     DB_PASSWORD=your_mysql_password
     
     AUTORUN_MIGRATIONS=true
     AUTORUN_WORKER=true
     AUTORUN_SCHEDULER=true
     ```
   *(Note: Setting `AUTORUN_WORKER=true` and `AUTORUN_SCHEDULER=true` allows the single container's supervisor to run background queue jobs and cron automatically without needing extra containers).*

5. **Set Container Port & Domain**:
   - Port: `80`
   - Set up your domain with HTTPS under the **Domains** tab.

6. **Deploy**:
   - Click **Deploy**.

---

## 📁 File Structure Reference

```text
├── Dockerfile                         # Production multi-stage build (Node Vite + PHP-FPM + Nginx)
├── docker-compose.yml                 # Multi-service stack (app, worker, scheduler, mysql, redis)
├── .dockerignore                      # Build context exclusion rules
├── .env.docker.example                # Pre-configured production environment template
├── docker/
│   ├── nginx/
│   │   ├── nginx.conf                 # Nginx core settings, performance, Gzip
│   │   └── default.conf               # Laravel virtual host, FastCGI, cache headers, /up route
│   ├── php/
│   │   ├── php.ini                    # Memory limit, file upload sizes, execution timeout
│   │   ├── opcache.ini                # High-performance OPcache & JIT settings
│   │   └── www.conf                   # PHP-FPM pool with clear_env=no for Docker env
│   ├── supervisor/
│   │   └── supervisord.conf           # Process supervisor (Nginx, PHP-FPM, Worker, Scheduler)
│   └── entrypoint.sh                  # Boot script (permissions, storage:link, DB check, migrations)
```

---

## 🛠️ Common Operations & Dokploy Terminal

Dokploy provides a web-based **Terminal** (Console) for running commands inside the container:

### Run Migrations or Seeders Manually
```bash
php artisan migrate --force
php artisan db:seed --force
```

### Clear or Re-cache Application
```bash
php artisan optimize:clear
php artisan optimize
```

### Regenerate Storage Link
```bash
php artisan storage:link
```

### Inspect Queue Status
```bash
php artisan queue:monitor default
```

---

## 🧪 Local Testing with Docker Compose

To test the entire production setup on your local machine before pushing to Dokploy:

1. Copy `.env.docker.example` to `.env`:
   ```bash
   cp .env.docker.example .env
   ```

2. Start the Docker Compose stack:
   ```bash
   docker compose up --build -d
   ```

3. Check container logs:
   ```bash
   docker compose logs -f app
   ```

4. Verify application in your browser:
   - Visit `http://localhost:8000`
   - Test health check: `http://localhost:8000/up` (returns HTTP 200 OK)

5. Stop the containers:
   ```bash
   docker compose down
   ```
