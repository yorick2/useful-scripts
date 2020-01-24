<?php
/*
A quick and simple password protected web page but not massively secure.
The password file "password_hash.txt" is needed to be created with the password going through 12 rounds of bcrypt encryption.
Also change the $cookieString string to introduce some variation in its usage accross sites its used on.
The content of the webpage is secured but the assets are not secured at all with this on its own.
*/
$message = '';
$loggedIn = false;
$cookieString = "szrew54e75r76r6er56etdrs5rstrd6trdt8r8yfr6rf";
if (isset($_COOKIE['PrivatePageLogin']) && $_COOKIE['PrivatePageLogin'] == md5($password.$cookieString)) {
  $loggedIn = true;
}
if(!$loggedIn && isset($_POST['pass']) == true) {
  $pwoptions   = ['cost' => 12,];
  $passhash    = password_hash($_POST['pass'], PASSWORD_BCRYPT, $pwoptions); 
  $hashedpass  = file_get_contents("password_hash.txt"); // location of hashed password file

  if (password_verify($_POST['pass'], $hashedpass) != true) {
    $message = 'This was not the right password. Please try again.';
  }else{    
    setcookie('PrivatePageLogin', md5($_POST['pass'].$cookieString));
    $loggedIn = true;
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="robots" content="noindex">
<meta name=”viewport” content=”width=device-width, initial-scale=1″>
<title>Password protected</title>
</head>
<body>
<?php if(!$loggedIn): ?>
  <form method="POST" action="">
    <p>Please enter your password.</p>
    Password: <input type="password" name="pass">
    <br>
    <?php if(strlen($message)): ?>
      <p><?= $message ?></p>
    <?php endif?>
    <br>
    <input type="submit" value="Submit">
  </form> 
<?php endif; ?>
<?php if($loggedIn): ?>
  <p>some content goes here</p>
<?php endif; ?>
</body></html>
