<?php
include_once("protect.php");
session_destroy();
header("Location: ../index.php");
