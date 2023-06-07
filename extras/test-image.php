<?php
if (isset($_POST['saveButton'])) {
  
  $message = '';

  $file = $_FILES['img_medicine'];

  $status = true;
  $targetFile = "none.jpeg";

  if (!empty($_FILES["img_medicine"]["name"])) {
      $allowedExtensions = array('png', 'jpg', 'jpeg');
      $baseName = basename($_FILES["img_medicine"]["name"]);
      $fileExtension = strtolower(pathinfo($baseName, PATHINFO_EXTENSION));

      // Check if the uploaded file has a valid extension
      if (in_array($fileExtension, $allowedExtensions)) {
          $targetFile = time() . $baseName;
          $status = move_uploaded_file($_FILES["img_medicine"]["tmp_name"], 'user_images/' . $targetFile);
      } else {
          // Invalid file format, handle the error as needed
          $message = "Invalid file format. Only PNG, JPG, or JPEG files are allowed.";
          $status = false;
      }
  }

  header("location:congratulation.php?goto_page=medicine_details.php&message=$message");
  exit;
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="your-php-script.php" method="POST" enctype="multipart/form-data">
  <input type="file" name="img_medicine[]" multiple>
  <ul id="photoList"></ul>
  <button type="submit" name="submit">Save</button>
</form>


</body>
<script src="plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
  var fileList = []; // Array to store the uploaded files

  // Add button click event
  $('#addButton').click(function() {
    var fileInput = document.getElementById('photoInput');
    var files = fileInput.files;

    // Iterate through selected files
    for (var i = 0; i < files.length; i++) {
      var file = files[i];

      // Add the file to the fileList array
      fileList.push(file);

      // Create a list item for each file
      var listItem = $('<li>').text(file.name);

      // Append the list item to the photoList
      $('#photoList').append(listItem);
    }
  });

  // Submit form event
  $('form').submit(function() {
    // Create a new FormData instance
    var formData = new FormData();

    // Append each file to the formData
    fileList.forEach(function(file) {
      formData.append('img_medicine[]', file);
    });

    // Append the formData to the form
    $(this).append(formData);

    // Submit the form
    return true;
  });
});



</script>
</html>