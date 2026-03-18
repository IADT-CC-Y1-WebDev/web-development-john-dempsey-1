<?php

class Session {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setFlash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function getFlash(): ?array {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    public static function setData(array $data): void {
        $_SESSION['form-data'] = $data;
    }

    public static function setErrors(array $errors): void {
        $_SESSION['form-errors'] = $errors;
    }

    public static function clearData(): void {
        unset($_SESSION['form-data']);
    }

    public static function clearErrors(): void {
        unset($_SESSION['form-errors']);
    }

    // Returns the previously submitted value for $key, or $default
    public static function old(string $key, $default = null) {
        $data = $_SESSION['form-data'] ?? null;
        if (is_array($data) && array_key_exists($key, $data)) {
            return $data[$key];
        }
        return $default;
    }

    // Returns the error message for $key, or null
    public static function error(string $key): ?string {
        $errors = $_SESSION['form-errors'] ?? null;
        if (is_array($errors) && array_key_exists($key, $errors)) {
            return $errors[$key];
        }
        return null;
    }

    // Returns true if $search matches the previously submitted value(s) for $key,
    // falling back to $default when no form data is present
    public static function chosen(string $key, $search, $default = null): bool {
        $data = $_SESSION['form-data'] ?? null;
        if (is_array($data) && array_key_exists($key, $data)) {
            $value = $data[$key];
            return is_array($value) ? in_array($search, $value) : strcmp($value, $search) === 0;
        }
        if ($default !== null) {
            return is_array($default) ? in_array($search, $default) : strcmp($default, $search) === 0;
        }
        return false;
    }
}
