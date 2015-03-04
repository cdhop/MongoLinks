<?php 
  include('lib/mongo_session_manager.php');
  include('lib/user.php');

  $client = new MongoClient();
  $database = $client->selectDB("mongolinks");
  $session = new MongoSessionManager($database);
  $user = new User($database);

  # Only want to see the current user's links
  $user_id = new MongoId($_SESSION['user_id']);
  $query = array('user_id' => $user_id);
  $collection = $database->selectCollection('links');
  $links = $collection->find($query)->sort(array('created_at' => -1));

  if(!$user->isLoggedIn())
  {
    header('location: login.php');
    exit;
  }

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

    <div class="add-block">
      <div class="container">
        <div class="row">
          <form id="addlink" action="insertlink.php" method="post" accept-charset="utf-8" role="form" class="form-horizontal" enctype="multipart/form-data">
            <legend>Add Link</legend>
            <div class="form-group">
              <?php if(isset($_SESSION['message'])): ?>
                <div class="col-sm-offset-2 col-sm-10">
                  <p class="text-info"><?php echo $_SESSION['message']; ?></p>
                </div>
                <?php unset($_SESSION['message']); ?>
              <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="title">Title</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" name="title" id="title" placeholder="Website Name"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="url">Address</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" name="url" id="url" placeholder="Website Address"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="description">Description</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" name="description" id="description" placeholder="Short description of the Website"/>
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="tags">Tags</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="tags" id="tags"/>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button class="btn btn-default" name="add" tabindex="3" type="submit" value="add">Add Link</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End add-block -->

    <div class="admin-block">
      <div class="container">
        <div class="row">
          <div class="table-responsive">
          <table class="table table-striped" >
            <legend>Your Links</legend>
            <thead>
              <tr>
                <th width="*">Link</th>
                <th width="35%">Description</th>
                <th width="25%">Tags</th>
                <th width="15%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while($link = $links->getNext()): ?>
               <tr> 
                 <td>
                   <a href="<?php echo $link['url']; ?>" target="_blank"><?php echo $link['title']; ?></a>
                 </td>
                 <td>
                   <?php echo $link['description']; ?>
                 </td>
                 <td>
                   <?php echo implode(", ", $link['tags']); ?>
                </td>
                <td>
                  <a type="button" class="btn btn-primary" alt="Edit" href="edit.php?id=<?php echo $link['_id']; ?>">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                  </a>

                  <a type="button" class="btn btn-danger" alt="Remove" href="delete.php?id=<?php echo $link['_id']; ?>">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                  </a>
                </td>
              </tr>
             <?php endwhile;?>
           </tbody>
          </table>
          </div>
        </div>
      </div>
    </div>
    <!-- End admin-block -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
