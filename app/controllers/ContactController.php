<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../models/Contact.php';
require_once __DIR__ . '/../core/Middleware.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class ContactController {
    private $contactModel;

    public function __construct() {
        $this->contactModel = new Contact();
    }

    public function getContacts($decodedToken) {
        $userId = $decodedToken->user_id; // Extract user ID from token

        // Fetch only contacts belonging to this user
        $clients = $this->contactModel->getContacts($userId);
        
        echo json_encode($clients);
    }

    public function getContactById($decodedToken, $contact_id) {

        $userId = $decodedToken->user_id;

        $contacts = $this->contactModel->getContactById($userId, $contact_id);
        echo json_encode($contacts);
    }
    public function createContact() {

        $decodedToken = Middleware::checkAuth();
        $userId = $decodedToken->user_id;

        $data = json_decode(file_get_contents("php://input"));
        if (!$data->name) {
            echo json_encode(["error" => "Name is requred!"]);
            return;
        }

        if ($this->contactModel->createContact($userId, $data->name, $data->alternative, $data->address1, $data->address2, $data->address3, $data->town, $data->region, $data->postcode, $data->email1, $data->email2)) {
            echo json_encode(["message" => "Contact saved successfully"]);
        } else {
            echo json_encode(["error" => "Error while saving contact!"]);
        }
    }

    public function deleteContact($id) {
        // Check authentication
        $decodedToken = Middleware::checkAuth();
        $userId = $decodedToken->user_id;
    
        // Fetch the contact to verify ownership
        $contact = $this->contactModel->getContactById($userId, $id);
    
        if (!$contact) {
            echo json_encode(["error" => "Contact not found!"]);
            return;
        }
    
        // Security check: Ensure the contact belongs to the authenticated user
        if ($contact['created_by'] !== $userId) {
            echo json_encode(["error" => "Unauthorized to delete this contact!"]);
            return;
        }
    
        // Proceed with deletion if authorized
        if ($this->contactModel->deleteContact($id, $userId)) {
            echo json_encode(["message" => "Contact deleted successfully"]);
        } else {
            echo json_encode(["error" => "Error while deleting contact!"]);
        }
    }    
    
}