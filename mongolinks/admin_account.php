<?php 
  include('lib/mongo_session_manager.php');
  include('lib/user.php');

  $client = new MongoClient();
  $database = $client->selectDB("mongolinks");
  $session = new MongoSessionManager($database);
  $user = new User($database);

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
    <title>FileBox</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>

    <?php include('_nav.php'); ?> 

    <div class="account-block">
      <div class="container">
        <div class="row">
          <form id="changepass" action="changeaccount.php" method="post" accept-charset="utf-8" role="form" class="form-horizontal">
            <legend>Manage Account</legend>
            <div class="form-group">
              <?php if(isset($_SESSION['message'])): ?>
                <div class="col-sm-offset-2 col-sm-10">
                  <p class="text-info"><?php echo htmletities($_SESSION['message']); ?></p>
                </div>
                <?php unset($_SESSION['message']); ?>
              <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="username">Username </label>
                <div class="col-sm-10">
                  <input class="form-control" tabindex="1" type="text" name="username" id="username" autocomplete="off" value="<?php echo htmlentities($user->__get('username')); ?> " disabled />
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="password">Password </label>
              <div class="col-sm-10">
                <input class="form-control" tabindex="2" type="password" name="password" id="password"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="confirm">Confirm Password </label>
              <div class="col-sm-10">
                <input class="form-control" tabindex="2" type="password" name="confirm" id="confirm"/>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button class="btn btn-default" name="signup" tabindex="3" type="submit" value="update">Update Account</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End Account -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
