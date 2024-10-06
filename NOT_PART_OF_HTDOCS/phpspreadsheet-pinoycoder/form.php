<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Import Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Upload Your File</h2>
    <form action="process.php" enctype="multipart/form-data" method="post">
        <label for="data_upload">
            Import Data
        </label>
        <input type="file" name="data_upload" id="data_upload">
        <button type="submit" name="submit">Upload</button>
    </form>
</body>
</html>