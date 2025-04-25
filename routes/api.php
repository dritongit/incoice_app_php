<?php
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/InvoiceController.php';
require_once __DIR__ . '/../app/controllers/ContactController.php';
require_once __DIR__ . '/../app/controllers/ArticleController.php';

$authController = new AuthController();
$invoiceController = new InvoiceController();
$contactController = new ContactController();
$articleController = new ArticleController();

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

Router::add("POST", "/register", [$authController, "register"]);
Router::add("POST", "/login", [$authController, "login"]);

Router::add("POST", "/invoice_add", [$invoiceController, "createInvoice"], [Middleware::class, "checkAuth"]);
Router::add("GET", "/invoices", [$invoiceController, "getInvoices"], [Middleware::class, "checkAuth"]);
Router::add("GET", "/invoice_subs/{id}", [$invoiceController, "getInvoicesWithSub"], [Middleware::class, "checkAuth"]);

Router::add("POST", "/contact_add", [$contactController, "createContact"], [Middleware::class, "checkAuth"]);
Router::add("GET", "/contacts", [$contactController, "getContacts"], [Middleware::class, "checkAuth"]);
Router::add("GET", "/contact/{id}", [$contactController, "getContactById"], [Middleware::class, "checkAuth"]);
Router::add("DELETE", "/contact_delete/{id}", [$contactController, "deleteContact"], [Middleware::class, "checkAuth"]);

Router::add("GET", "/articles", [$articleController, "getAtricles"], [Middleware::class, "checkAuth"]);
Router::add("GET", "/article/{id}", [$articleController, "getArticleById"], [Middleware::class, "checkAuth"]);
Router::add("DELETE", "/article/{id}", [$articleController, "deleteArticle"], [Middleware::class, "checkAuth"]);
Router::add("POST", "/article_add", [$articleController, "createArticle"], [Middleware::class, "checkAuth"]);
Router::add("PUT", "/article_update/{id}", [$articleController, "updateArticle"], [Middleware::class, "checkAuth"]);

Router::add('GET', '/test', function () {
    echo json_encode(["message" => "API is working"]);
});

Router::dispatch($method, $uri);
?>
