<?php
session_start();

if (!isset($_SESSION['cards_reviewed'])) {
    $_SESSION['cards_reviewed'] = 0;
}
$_SESSION['cards_reviewed']++;
