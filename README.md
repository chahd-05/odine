# Odin Brief

A Laravel web application.

## Prerequisites

- **PHP**: ^8.2
- **Composer**
- **Node.js** & **NPM**

## Installation

1. Clone the repository.
2. Run the automated setup script:

   ```bash
   composer run setup
   ```

   This helper script will automatically:
   - Install PHP dependencies (`composer install`)
   - Create the `.env` file from `.env.example`
   - Generate the application key
   - Run database migrations
   - Install and build frontend dependencies (`npm install` && `npm run build`)

## Development

To start the development environment (server, queue, logs, and vite):

```bash
composer run dev
```

## Testing

To run the application tests:

```bash
composer run test
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
