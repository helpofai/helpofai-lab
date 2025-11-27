# Project Overview

This is the helpofai-lab application, a CodePen clone featuring a modern frontend built with Vue.js and Inertia.js. It utilizes the Monaco Editor for its code editing capabilities, suggesting a focus on interactive code development and display.

## Technologies Used

*   **Backend:** PHP 8.2+, Laravel 12
*   **Frontend:** Vue.js 3, Inertia.js, Tailwind CSS
*   **Build Tool:** Vite
*   **Package Managers:** Composer (PHP), npm (Node.js)
*   **Database:** SQLite (default, configurable)
*   **Other:** Laravel Jetstream, Laravel Sanctum, Tightenco Ziggy, Monaco Editor

## Setup Instructions

To get this project up and running, follow these steps:

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd codepen-clone/codepen-clone
    ```

2.  **Install PHP and Node.js dependencies and set up the application:**
    This project includes a convenient `setup` script in `composer.json` that handles most of the initial configuration.
    ```bash
    composer run setup
    ```
    This command will:
    *   Install PHP dependencies via Composer.
    *   Create your `.env` file from `.env.example` if it doesn't exist.
    *   Generate an application key.
    *   Run database migrations.
    *   Install Node.js dependencies via npm.
    *   Build the frontend assets for production.

    _Note: If you encounter issues with `npm install` or `npm run build` during the setup, ensure you have Node.js and npm installed correctly._

3.  **Configure your `.env` file:**
    The `setup` command will copy `.env.example` to `.env`. You may need to adjust database credentials or other environment variables in the newly created `.env` file to match your local environment. By default, it's set up to use SQLite.

## Building and Running

### Development

To run the application in development mode with hot module replacement (HMR) for frontend changes, and other development tools, use the provided `dev` script:

```bash
composer run dev
```

This command will concurrently start:
*   The Laravel development server (`php artisan serve`).
*   A queue listener (`php artisan queue:listen`).
*   A log tailer (`php artisan pail`).
*   The Vite development server (`npm run dev`).

You can access the application in your web browser, usually at `http://127.0.0.1:8000`.

### Production Build

To build the frontend assets for a production deployment:

```bash
npm run build
```

This command compiles and minifies your JavaScript and CSS files using Vite.

## Testing

To run the project's tests:

```bash
composer run test
```

This command will clear the configuration cache and then execute all defined tests using PHPUnit.
