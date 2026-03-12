<?php
require 'db.php';
require 'LoanApplication.php';

$loan = new LoanApplication($pdo);

$name = $_POST['name'];
$amount = $_POST['amount'];
$bank = $_POST['bank'];
$insurance = isset($_POST['insurance']) ? 1 : 0;
$loan_term = $_POST['loan_term'];

$loan->add($name, $amount, $bank, $insurance, $loan_term);

header("Location: index.php");
exit();