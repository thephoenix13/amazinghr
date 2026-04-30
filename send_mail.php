<?php
// ============================================================
//  AmazingHR — Contact Form Mailer
// ============================================================

define('TO_EMAIL',   'dhyan@amazing-hr.com');
define('FROM_EMAIL', 'dhyan@amazing-hr.com');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Collect and sanitise fields
$name        = trim(strip_tags($_POST['name']             ?? ''));
$designation = trim(strip_tags($_POST['designation']      ?? ''));
$mobile      = trim(strip_tags($_POST['mobile']           ?? ''));
$email       = trim(strip_tags($_POST['email']            ?? ''));
$company     = trim(strip_tags($_POST['company']          ?? ''));
$service     = trim(strip_tags($_POST['service_interest'] ?? ''));
$message     = trim(strip_tags($_POST['message']          ?? ''));

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

// ---- Email 1: Notification to AmazingHR ----
$to_body  = "New enquiry from the AmazingHR website\n";
$to_body .= "========================================\n\n";
$to_body .= "Name        : $name\n";
$to_body .= "Designation : $designation\n";
$to_body .= "Mobile      : $mobile\n";
$to_body .= "Email       : $email\n";
if ($company) $to_body .= "Company     : $company\n";
if ($service) $to_body .= "Service     : $service\n";
$to_body .= "\nMessage:\n$message\n";

$to_subject = "New Enquiry from $name — AmazingHR Website";

$to_headers  = "From: AmazingHR Website <" . FROM_EMAIL . ">\r\n";
$to_headers .= "Reply-To: $email\r\n";
$to_headers .= "X-Mailer: PHP/" . phpversion();

$sent = mail(TO_EMAIL, $to_subject, $to_body, $to_headers);

// ---- Email 2: Confirmation to the enquirer ----
$confirm_body  = "Dear $name,\n\n";
$confirm_body .= "Thank you for reaching out to Amazing HR.\n\n";
$confirm_body .= "We have received your enquiry and a member of our team will get back to you within one business day.\n\n";
$confirm_body .= "For urgent requirements, you can reach us at:\n";
$confirm_body .= "Email : dhyan@amazing-hr.com\n";
$confirm_body .= "Phone : +91-120-2658379\n\n";
$confirm_body .= "Here is a summary of what you submitted:\n";
$confirm_body .= "----------------------------------------\n";
$confirm_body .= "Name        : $name\n";
$confirm_body .= "Designation : $designation\n";
$confirm_body .= "Mobile      : $mobile\n";
if ($company) $confirm_body .= "Company     : $company\n";
if ($service) $confirm_body .= "Service     : $service\n";
$confirm_body .= "\nMessage:\n$message\n";
$confirm_body .= "----------------------------------------\n\n";
$confirm_body .= "Warm regards,\n";
$confirm_body .= "Amazing HR Services Pvt Ltd\n";
$confirm_body .= "Integrity | Passion | Excellence\n";
$confirm_body .= "www.amazing-hr.com\n";

$confirm_subject = "We've received your enquiry — Amazing HR";

$confirm_headers  = "From: Amazing HR Services <" . FROM_EMAIL . ">\r\n";
$confirm_headers .= "Reply-To: " . FROM_EMAIL . "\r\n";
$confirm_headers .= "X-Mailer: PHP/" . phpversion();

mail($email, $confirm_subject, $confirm_body, $confirm_headers);

if ($sent) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Mail server error']);
}
