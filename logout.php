<?php
session_start();
require("functions.php");
logout();
redirect_to("page_login.php");