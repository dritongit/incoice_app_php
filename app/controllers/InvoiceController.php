<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/Contact.php';
require_once __DIR__ . '/../core/Middleware.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class InvoiceController {
    private $invoiceModel;
    private $contactModel;

    public function __construct() {
        $this->invoiceModel = new Invoice();
        $this->contactModel = new Contact();
    }

    public function createInvoice() {
        $decodedToken = Middleware::checkAuth();
        $userId = $decodedToken->user_id;

        $data = json_decode(file_get_contents("php://input"));
        if (!$data->amount) {
            echo json_encode(["error" => "Shuma është e detyrueshme!"]);
            return;
        }

        if ($this->invoiceModel->createInvoice($userId, $data->amount)) {
            echo json_encode(["message" => "Fatura u krijua me sukses!"]);
        } else {
            echo json_encode(["error" => "Gabim gjatë krijimit të faturës!"]);
        }
    }

    public function getInvoices($decodedToken) {
        // $decodedToken = Middleware::checkAuth();
        $userId = $decodedToken->user_id;

        $invoices = $this->invoiceModel->getInvoices($userId);
        echo json_encode($invoices);
    }

    public function getInvoicesWithSub($id) {
        $decodedToken = Middleware::checkAuth();
        $userId = $decodedToken->user_id;

        $invoices = $this->invoiceModel->getInvoice($id);

      
        foreach ($invoices as &$invoice) {
            $invoice['invoice_sub'] = $this->invoiceModel->getInvoiceSub($invoice['pk_invoice_id']);
        }
        
        $invoice['contact_1'] = $this->contactModel->getContactById($userId, $invoice['fk_contact1']);
        $invoice['contact_2'] = $this->contactModel->getContactById($userId, $invoice['fk_contact2']);
        $invoice['payments'] = $this->invoiceModel->getInvoicePayments($invoice['pk_invoice_id']);
        echo json_encode($invoices);
    }
}
?>
