<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'non authentifié']);
    exit;
}
function currentUserId(): int { return (int)($_SESSION['user_id'] ?? 0); }
function currentRole(): string { return $_SESSION['role'] ?? 'client'; }
function adminFilter(string $alias='t'): array {
    if (currentRole() === 'admin') return ['', []];
    return [" WHERE {$alias}.user_id = ? ", [currentUserId()]];
}
