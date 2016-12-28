<?php 
  include('lib/mongo_session_manager.php');
  include('lib/user.php');

  $client = new MongoClient();
  $database = $client->selectDB("mongolinks");
  $session = new MongoSessionManager($database);
  $user = new User($database);
  $collection = $collection = $database->selectCollection('links');

  $tag = $_GET['tag'];
  $query = array('tags' => $tag);

  $links = $collection->find($query)->sort(array('created_at' => -1))->limit(20);

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

    <div class="recent-block">
      <div class="container">
        <div class="row">
          <div class="table-responsive">
          <table class="table table-striped" >
            <legend>Recent Links</legend>
            <thead>
              <tr>
                <th width="*">Title</th>
                <th width="35%">Description</th>
		            <th width="25%">Tags</th>
              </tr>
            </thead>
            <tbody>
              <?php while($link = $links->getNext()): ?>
               <tr> 
                 <td>
                   <a href="<?php echo htmlentities($link['url']); ?>" target="_blank"><?php echo htmlentities($link['title']); ?></a>
                 </td>
                 <td>
                   <?php echo htmlentities($link['description']); ?>
                 </td>
                 <td>
                   <?php echo htmlentities(implode(", ", $link['tags'])); ?>
                </td>
              </tr>
             <?php endwhile;?>
           </tbody>
          </table>
          </div>
        </div>
      </div>
    </div>
    <!-- End recent-block -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
