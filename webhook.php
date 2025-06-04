<?php
// Endpoint específico para webhook
require_once 'controllers/WebhookController.php';

$controller = new WebhookController();
$controller->statusUpdate();
?>