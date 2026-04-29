<?php
// ============================================================
//  AmazingHR — Contact Form Mailer
//  Fill in FROM_EMAIL with your Hostinger email address
// ============================================================

define('TO_EMAIL',   'dhyan.chauhan.amazinghr@gmail.com');
define('FROM_EMAIL', 'inspire@amazinghr.org'); // ← your Hostinger email here

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Collect and sanitise fields
$name        = trim(strip_tags($_POST['name']        ?? ''));
$designation = trim(strip_tags($_POST['designation'] ?? ''));
$mobile      = trim(strip_tags($_POST['mobile']      ?? ''));
$email       = trim(strip_tags($_POST['email']       ?? ''));
$company     = trim(strip_tags($_POST['company']     ?? ''));
$service     = trim(strip_tags($_POST['service_interest'] ?? ''));
$message     = trim(strip_tags($_POST['message']     ?? ''));

// Validate required fields
if (!$name || !$designation || !$mobile || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Required fields missing']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Build email body
$body  = "New enquiry from the AmazingHR website\n";
$body .= "========================================\n\n";
$body .= "Name        : $name\n";
$body .= "Designation : $designation\n";
$body .= "Mobile      : $mobile\n";
$body .= "Email       : $email\n";
if ($company) $body .= "Company     : $company\n";
if ($service) $body .= "Service     : $service\n";
$body .= "\nMessage:\n$message\n";

$subject = "New Enquiry from $name — AmazingHR Website";

$headers  = "From: AmazingHR Website <" . FROM_EMAIL . ">\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

if (mail(TO_EMAIL, $subject, $body, $headers)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Mail server error']);
}
