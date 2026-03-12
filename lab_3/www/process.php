<?php
session_start();

$fullname = htmlspecialchars($_POST['fullname'] ?? '');
$amount = htmlspecialchars($_POST['amount'] ?? '');
$bank = htmlspecialchars($_POST['bank'] ?? '');
$term = htmlspecialchars($_POST['term'] ?? '');
$insurance = isset($_POST['insurance']) ? 'Да' : 'Нет';

$errors = [];

if (empty($fullname)) {
    $errors[] = "ФИО не может быть пустым";
}

if (empty($amount) || !is_numeric($amount) || $amount < 1000 || $amount > 10000000) {
    $errors[] = "Сумма кредита должна быть от 1000 до 10 000 000 ₽";
}

if (empty($bank)) {
    $errors[] = "Выберите банк";
}

if (empty($term)) {
    $errors[] = "Выберите срок кредита";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: form.html");
    exit();
}

$_SESSION['fullname'] = $fullname;
$_SESSION['amount'] = $amount;
$_SESSION['bank'] = $bank;
$_SESSION['term'] = $term;
$_SESSION['insurance'] = $insurance;

$data = implode(';', [
    date('Y-m-d H:i:s'),
    $fullname,
    $amount,
    $bank,
    $term,
    $insurance
]) . "\n";

file_put_contents(__DIR__ . '/data.txt', $data, FILE_APPEND | LOCK_EX);

header("Location: index.php");
exit();