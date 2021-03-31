<?php

$name = $_GET['name'] ?? 'World';

header('Content-type: text/html; charset=utf-8');

printf('Hello %s', htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));
