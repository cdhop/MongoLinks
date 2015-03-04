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

  $updated_link = array(
    'title'         => sanitize($_POST['title']),
    'url'           => sanitize($_POST['url']),
    'description'   => sanitize($_POST['description']),
    'tags'          => array_map('trim', explode(',',sanitize($_POST['tags']))),
    'user_id'       => $user->__get('_id'),
  );

  $link_id = sanitize($_POST['link_id']);
  $user_id = new MongoId($_SESSION['user_id']); 

  $query = array('_id'=> new MongoId($link_id), 'user_id'=> new MongoId($user_id));
  $change = array('$set' => $updated_link);

  try
  {
    $collection->update($query,$change);
    $_SESSION['message'] = "Link Updated Successfully";
  }
  catch(MongoGridFSException $e)
  {
    $_SESSION['message'] = "Error Updating Link";
  }
  
  header('location: admin_links.php');

?>
