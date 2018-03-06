<?php

namespace Bulveyz\Auth;

ob_start();

use RedBeanPHP\R;

class Auth
{

  public $errors = [];
  private $loadUser = null;
  private $token = null;

  public function __construct()
  {
    if (isset($_SESSION['auth'])) {
      unset($_SESSION['guest']);
    } else {
      $_SESSION['guest'] = 1;
    }
  }

  public function userExit($token = false)
  {
    if ($token) {
      $load_session = R::findOne('auth', 'token = ?', array($token));

      if ($load_session) {
        R::trash($load_session);
      }
    }

    unset($_SESSION['auth']);

    if (isset($_COOKIE['auth'])) {
      setcookie('auth','', time() - 3600 * 24 * 7);
      ob_end_flush();
    }

    redirect('/');
  }

  public function signUp()
  {
    if ($_POST['name'] == '' || $_POST['email'] == '' || $_POST['password'] == '' || $_POST['confirmPassword']) {
      $this->errors[] = 'Fill in all the fields';
    }
    if (R::count('users', 'name = ?', array($_POST['name'])) > 0) {
      $this->errors[] = 'A user with this name already exists';
    }
    if (R::count('users', 'email = ?', array($_POST['email'])) > 0) {
      $this->errors[] = 'This account is already taken! Please Login';
    }
    if ($_POST['password'] != $_POST['confirmPassword']) {
      $this->errors[] = 'The passwords you entered do not match!';
    }
    if (strlen($_POST['password']) < 8) {
      $this->errors[] = 'The password must contain at least 8 characters!';
    }

    if (empty($this->errors)) {
      $createUser = R::dispense('users');
      $createUser->name = strip_tags($_POST['name']);
      $createUser->email = strip_tags($_POST['email']);
      $createUser->password = strip_tags($_POST['password']);
      R::store($createUser);

      redirect('/login');
    } else {
      echo "<div class='alert alert-danger' role='alert'>" . array_shift($this->errors) . "</div>";
    }
  }

  public function signIn()
  {
    if ($_POST['email'] == '' || $_POST['password'] == '') {
      $this->errors[] = 'Fill in all the fields';
    } else {
      $this->loadUser = R::findOne('users', 'email = ?', array($_POST['email']));
    }
    if (R::count('users', 'email = ?', array($_POST['email'])) == 0) {
      $this->errors[] = 'Account not found! You can <a href='.'/register'.'>register new account</a>';
    }
    if ($_POST['password'] != $this->loadUser['password']) {
      $this->errors[] = 'Wrong login or password';
    }

    if (empty($this->errors)) {
      $this->token = token();

      $authorization = R::dispense('authorization');
      $authorization->user_id = $this->loadUser['id'];
      $authorization->token = $this->token;

      if (R::store($authorization)) {
        $_SESSION['auth'] = [
          'id' => $this->loadUser['id'],
          'name' => $this->loadUser['name'],
          'password' => $this->loadUser['password'],
          'token' => $this->token
        ];

        if (isset($_POST['remember']))
        {
          setcookie('auth',$this->token, time() + 3600 * 24 * 3);
          ob_end_flush();
        }

        redirect('/');
      } else {
        exit('Error authorization');
      }
    } else {
      echo "<div class='alert alert-danger' role='alert'>" . array_shift($this->errors) . "</div>";
    }
  }

  public function checkAuthWithSession()
  {
    $loadSession = R::findOne('authorization', 'token = ?', array($_SESSION['auth']['token']));

    if (!$loadSession) {
      $this->userExit();
    }
  }

  public function checkAuthWithCookie()
  {
    $loadSession = R::findOne('auth', 'token = ?', array($_COOKIE['auth']));

    if ($loadSession) {
      $loadUser = R::load('users', $loadSession['user_id']);

      $_SESSION['auth'] = [
          'id' => $loadUser['id'],
          'name' => $loadUser['name'],
          'password' => $loadUser['password'],
          'token' => $_COOKIE['auth']
      ];
    } else {
     $this->userExit();
    }
  }

  public function requestReset()
  {
    if (isset($_POST['requestReset'])) {
      if ($_POST['email'] == '') {
        $this->errors[] = 'Input Email!';
      }
      if (!R::count('users', 'email = ?', array($_POST['email']))) {
        $this->errors[] = 'This account not found!';
      }
      if (empty($this->errors)) {
        $_SESSION['reset'] = [
            'token' => token(),
            'email' => $_POST['email']
        ];

        mail($_POST['email'], 'Reset Password', "Your link for reset password: ".'https://bulveyz2.0.test/restore/'.$_SESSION['reset']['token']."", 'BulveyzTeam');

        echo "<div class='alert alert-success' role='alert'>A password recovery link has been sent to the email</div>";
      } else {
        echo "<div class='alert alert-danger' role='alert'>" . array_shift($this->errors) . "</div>";
      }
    }
  }

  public function resetPassword($token)
  {
    if (isset($_SESSION['reset']) &&  $_SESSION['reset']['token'] == $token) {
      if (isset($_POST['resetPassword'])) {
        if ($_POST['password'] == '') {
          $this->errors[] = 'Input new password!';
        }
        if ($_POST['password'] != $_POST['password2']) {
          $this->errors[] = 'The passwords you entered do not match!';
        }
        if (empty($this->errors)) {
          $changeData = R::findOne('users', 'email = ?', array($_SESSION['reset']['email']));
          $changeData->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

          if (R::store($changeData)) {
            $loadAuthSession = R::findAll('auth', 'user_id = ?', array($changeData['id']));

            foreach ($loadAuthSession as $load)
            {
              R::trash('auth', $load['id']);
            }

            mail($_POST['email'], 'Reset Password', "Your password will be restored", 'BulveyzTeam');

            unset($_SESSION['reset']);
            redirect('/login');
          } else {
            exit('Error DataChange');
          }
        } else {
          echo "<div class='alert alert-danger' role='alert'>" . array_shift($this->errors) . "</div>";
        }
      }
    } else {
      exit('404');
    }
  }

  public static function user()
  {
    if (isset($_SESSION['auth'])) {
      return R::load('users', $_SESSION['auth']['id']);
    }
  }

  public function authorization()
  {
    if (isset($_POST['signUp'])) {
      $this->signUp();
    } elseif (isset($_POST['signIn'])) {
      $this->signIn();
    } elseif(isset($_SESSION['auth'])) {
      $this->checkAuthWithSession();
    } elseif (isset($_COOKIE['auth'])) {
      $this->checkAuthWithCookie();
    }
  }
}