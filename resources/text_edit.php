<?php
#http://www.formget.com/login-form-in-php/
require_once('isActive.php');
require_once('edit_text.php');
$login_session=$_SESSION['user_name'];
$key = $_SESSION['user_key'];
$hash = $_SESSION['user_hash'];

#test data file
$new_file = false;
#txte file type is txt encrypted
$data_file = '../data/' . $hash . '.txte';
$confirm = '';

if (isset($_POST['create'])) {
  $file = fopen($data_file, "w") or die("Unable to open file!");
  fclose($file);
}

if (isset($_POST['textform'])) {
  set_text($_POST['textform'], $data_file, $key);  
  $confirm = 'File saved!';
}

if (file_exists($data_file) ) {
  #open file
  $text = get_text($data_file, $key); 
} else {
  #flag to create file  
  $new_file = true; 
}

?>

<html>
<head>
<title>Edit Data</title>
</head>
<body>
<div id="view">
<p><b id="welcome">Welcome: <i><?php echo $login_session; ?></i></b></p>
<hr>
<p>
<?php 

print $confirm;
print '</p>';

if ($new_file) {
  print '<p>It looks like you are missing a data file, click to create one</p>';
  print '<form action="text_edit.php" method="post">';
  print '<input name="create" type="submit" value="Create File">';

}
 else {
  print '<p>Text Input<p><div id="text_edit">';
  print '<form action="text_edit.php" method="post">';
  print '<textarea rows="10" cols="75" name="textform">';
  print $text;
  print '</textarea>';
  print '</p><p>';
  print '<input name="submit" type="submit" value="Save">';
  print '</form></div></p>';
}
 ?>
</p>
<?php require_once('footer.php') ?>
