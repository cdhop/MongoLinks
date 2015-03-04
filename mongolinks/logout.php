<?php

	include('lib/mongo_session_manager.php');
	include('lib/user.php');

	$client = new MongoClient();
	$database = $client->selectDB("mongolinks");
	$session = new MongoSessionManager($database);
	$user = new User($database);
	$user->logout();

	header('location: login.php');
	exit;
?>