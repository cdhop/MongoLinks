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

  $link = $collection->findOne($query);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MongoLinks</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>

    <?php include('_nav.php'); ?>
    
    <div class="edit-block">
      <div class="container">
        <div class="row">
          <form id="editlink" action="updatelink.php" method="post" accept-charset="utf-8" role="form" class="form-horizontal">
            <legend>Edit Link</legend>
            <input type="hidden" name="link_id" value="<?php echo htmlentities($id); ?>"/>
            <div class="form-group">
              <?php if(isset($_SESSION['message'])): ?>
                <div class="col-sm-offset-2 col-sm-10">
                  <p class="text-info"><?php echo htmlentities($_SESSION['message']); ?></p>
                </div>
                <?php unset($_SESSION['message']); ?>
              <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="title">Title</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" name="title" id="title" value="<?php echo htmlentities($link['title']) ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="url">Address</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" name="url" id="url" value="<?php echo htmlentities($link['url']) ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="description">Description</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" name="description" id="description" value="<?php echo htmlentities($link['description']) ?>" />
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="tags">Tags</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="tags" id="tags" value="<?php echo htmlentities(implode(', ', $link['tags'] )); ?>"/>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="id" value="<?php echo htmlentities($file['_id']); ?>">
                <button class="btn btn-default" name="update" tabindex="3" type="submit" value="update">Update Link</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End edit-block -->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
