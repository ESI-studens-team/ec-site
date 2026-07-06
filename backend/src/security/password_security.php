<?php

function hashPassword(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword(string $password, string $hashedPassword): bool
{
    return password_verify($password, $hashedPassword);
}