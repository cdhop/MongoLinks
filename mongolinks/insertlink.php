<?php 
  include('lib/mongo_session_manager.php');
  include('lib/user.php');
  include('lib/sanitize.php');

  $client = new MongoClient();
  $database = $client->selectDB("mongolinks");
  $session = new MongoSessionManager($database);
  $user = new User($database);
  $collection = $database->selectCollection('links');

  if(!$user->isLoggedIn())
  {
    header('location: login.php');
    exit;
  }

  $new_link = array(
    'title'         => sanitize($_POST['title']),
    'url'           => sanitize($_POST['url']),
    'description'   => sanitize($_POST['description']),
    'tags'          => array_map('trim', explode(',',sanitize($_POST['tags']))),
    'user_id'       => $user->__get('_id'),
    'created_at'    => new MongoDate(),
  );

  try
  {
    $id = $collection->insert($new_link);
    $_SESSION['message'] = "Link Added Successfully";
  }
  catch(MongoGridFSException $e)
  {
    $_SESSION['message'] = "Error Adding Link";
  }
  
  header('location: admin_links.php');

?>
