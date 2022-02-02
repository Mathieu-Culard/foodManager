<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>foodManager Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="/../assets/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href='/../assets/css/style.css' ?>
</head>

<body>
  <?php
  if (!empty($_SESSION['userId'])) {
    include __DIR__ . '/../partials/nav.tpl.php';
  }
  ?>