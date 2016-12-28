<?php 
  include('lib/mongo_session_manager.php');
  include('lib/user.php');

  $client = new MongoClient();
  $database = $client->selectDB("mongolinks");
  $session = new MongoSessionManager($database);
  $user = new User($database);

  $map = new MongoCode("function() {".
           "for (i = 0; i < this.tags.length; i++) {".
             "emit(this.tags[i], 1);".
           "}". 
         "}");

  $reduce = new MongoCode("function(key, values) {".
           "var count = 0;".
           "for (var i = 0; i < values.length; i++){".
             "count += values[i];".
           "}".
           "return count;".
         "}");

  $command = array(
           'mapreduce' => 'links',
           'map' => $map,
           'reduce' => $reduce,
           'out' => 'tagcount'
         );

  $database->command($command);

  $tags = iterator_to_array($database->selectCollection('tagcount')->find()->sort(array('value' => -1)));

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

    <div class="intro-block">
      <div class="container">
        <div class="row">
          <div class="col-xs-2">
            <!--<img class="img-responsive" src="images/filebox.png">-->
          </div>
          <div class="col-xs-9">
            <h1>MongoLinks <span class="text-muted">&raquo; Online Social Links</span></h1>
            <p class="lead">Share your favorite links online</p>
          </div>
        </div>
      </div>
    </div>
    <!-- End Intro Text -->

    <div class="recent-block">
      <div class="container">
        <div class="row">
          <div class="table-responsive">
          <table class="table table-striped" >
            <legend>Tags</legend>
            <thead>
              <tr>
                <th width="*">Tag</th>
                <th width="35%">Count</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($tags as $tag): ?>
               <tr> 
                 <td>
                   <a href="/tagview.php?tag=<?php echo htmlentities($tag['_id']); ?>"><?php echo htmlentities($tag['_id']); ?></a>
                 </td>
                 <td>
                   <?php echo htmlentities($tag['value']); ?>
                 </td>
              </tr>
             <?php endforeach;?>
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
