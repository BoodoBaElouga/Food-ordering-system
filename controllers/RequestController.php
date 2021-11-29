<?php
include '../includes/connect.php';
class Router extends DataTrafficWithDatabase {
  private $view;
  private $crtlr;
  public function searchController()
  {
      $success=false;

      $username = $_POST['username'];
      $password = $_POST['password'];

      $request = self::getConnection()->prepare(
          "SELECT * FROM users WHERE username='$username' 
                 AND password='$password' AND role='Administrator' AND not deleted;");
      $request->execute();
      $result = $request->fetch(PDO::FETCH_ASSOC);
      while($row = mysqli_fetch_array($result))
      {
          $success = true;
          $user_id = $row['id'];
          $name = $row['name'];
          $role= $row['role'];
      }
      if($success == true)
      {
          session_start();
          $_SESSION['admin_sid']=session_id();
          $_SESSION['user_id'] = $user_id;
          $_SESSION['role'] = $role;
          $_SESSION['name'] = $name;

          header("location: ../admin-page.php");
      }
      else
      {
          $result = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='Customer' AND not deleted;");
          while($row = mysqli_fetch_array($result))
          {
              $success = true;
              $user_id = $row['id'];
              $name = $row['name'];
              $role= $row['role'];
          }
          if($success == true)
          {
              session_start();
              $_SESSION['customer_sid']=session_id();
              $_SESSION['user_id'] = $user_id;
              $_SESSION['role'] = $role;
              $_SESSION['name'] = $name;
              header("location: ../index.php");
          }
          else
          {
              header("location: ../login.php");
          }
      }

      try {
          spl_autoload_register(function ($class) {
              require_once('models/' . $class . '.php');
          });
          $url = '';
          if (isset($_GET['url'])) {
              $url = explode('/', $_GET['url']);
              $controller = ucfirst(strtolower($url[0]));
              $controllerClass = $controller . 'Controller';
              $controllerFile = '../controller/' . $controllerClass . 'php';
              if (file_exists($controllerFile)) {
                  require_once($controllerFile);
                  $this->crtlr = new $controllerClass($url);
              } else {
                  throw new \Exception('Page not found', 1);
              }
          } else {
              require_once('../controller/LoginController.php');
          }
      } catch (\Exception $e) {
          echo $e;
      }
  }
}
