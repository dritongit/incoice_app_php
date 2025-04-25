
# Invoice App (PHP)

A simple invoice management application built using PHP. It enables basic operations for managing invoices, including creation, retrieval, updating, and deletion.

## Features

- **CRUD operations** (Create, Read, Update, Delete) for invoice management.
- Simple routing for handling web requests.
- MVC structure for organized and maintainable code.
- Easy to set up and extend.

## Installation

### Prerequisites

- PHP 7.4 or newer
- Composer
- A web server (Apache, Nginx, or PHP built-in server)

### Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/dritongit/incoice_app_php.git
   ```

2. **Navigate into the project directory**:
   ```bash
   cd incoice_app_php
   ```

3. **Install dependencies**:
   ```bash
   composer install
   ```

4. **Configure your web server**:
   Point your document root to the `public/` directory.

5. **Run the application**:
   You can quickly start a PHP development server using:
   ```bash
   php -S localhost:8000 -t public
   ```

   Open your browser and visit `http://localhost:8000`.

## Project Structure

```
incoice_app_php/
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── public/
│   └── index.php
├── routes/
│   └── web.php
├── composer.json
└── .gitignore
```

- `app/`: Contains MVC components.
- `public/`: Web accessible folder, entry point of the app.
- `routes/`: Application routes configuration.

## Contributing

Feel free to contribute! Fork this repository and create a pull request to propose improvements.

## License

This project is licensed under the [MIT License](LICENSE).
