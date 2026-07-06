<?php

$pdo = new PDO(
    'sqlite:../../database/app.sqlite'
);

$pdo->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);

return $pdo;