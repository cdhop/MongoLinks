<?php
  include('lib/mongo_session_manager.php');
  include('lib/user.php');

  $client = new MongoClient();
  $database = $client->selectDB("mongolinks");
  $session = new MongoSessionManager($database);
  $user = new User($database);
  $collection = $database->selectCollection('links');

  $id = $_GET['id'];

  if(!$user->isLoggedIn())
  {
    header('location: login.php');
    exit;
  }

  $query = array('_id'=> new MongoId($id), 'user_id'=> new MongoId($_SESSION['user_id']));

  $collection->remove($query);

  header('location: admin_links.php');

?>
