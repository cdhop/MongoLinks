<?php 
  include('lib/mongo_session_manager.php');
  include('lib/user.php');
  include('lib/sanitize.php');

  $client = new MongoClient();
  $database = $client->selectDB("mongolinks");
  $session = new MongoSessionManager($database);
  $user = new User($database);

  $action = (!empty($_POST['login']) && ($_POST['login'] === 'login')) ? 'login' : 'show_form';

  switch($action)
  {
    case 'login':
      $username = sanitize($_POST['username']);
      $password = sanitize($_POST['password']);

      if($user->authenticate($username, $password))
      {
        header('location: index.php');
        exit;
      }
      else
      {
        $errorMessage = "Username/password did not match.";
        break;
      }
    case 'show_form':
    default:
      $errorMessage = Null;
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

    <div class="login-block">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form id="login" action="login.php" method="post" accept-charset="utf-8" role="form" class="form-horizontal">
              <legend>Login In</legend>
              <div class="form-group">
                <?php if(isset($errorMessage)): ?>
                  <div class="col-sm-offset-2 col-sm-10">
                    <p class="text-danger"><?php echo htmlentities($errorMessage); ?></p>
                  </div>
                <?php endif; ?>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 control-label" for="username">Username </label>
                  <div class="col-sm-10">
                    <input class="form-control" tabindex="1" type="text" name="username" id="username" autocomplete="off"/>
                  </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="password">Password </label>
                <div class="col-sm-10">
                  <input class="form-control" tabindex="2" type="password" name="password" id="password"/>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button class="btn btn-default" name="login" tabindex="3" type="submit" value="login">Log in</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- End Login -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
