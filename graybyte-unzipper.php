<?php
/*
Graybyt3 - Ex-Blackhat 🖤 | Ex Super Mod of Team_CC.
Now securing systems as a Senior Security Expert 🛡️.
I hack servers for fun, patch them to torture you.

"My life is a lie, and i'm living in this only truth.- Graybyt3"

WARNING: This code is for educational and ethical purposes only.
I am not responsible for any misuse or illegal activities.

WARNING: Steal my code, and I'll call you Pappu — there's no worse shame in this world than being called Pappu.
#FuCk_Pappu
*/
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dounzip'])) {
        if (!empty($_POST['zipfile'])) {
            $archive = $_POST['zipfile'];
            $destination = !empty($_POST['extpath']) ? $_POST['extpath'] : '.';
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            $zip = new ZipArchive();
            if ($zip->open($archive) === TRUE) {
                $zip->extractTo($destination);
                $zip->close();
                $currentDir = realpath($destination);
                $_SESSION['popup'] = ['location' => $currentDir];
            } else {
                $_SESSION['popup'] = ['error' => 'FAILED TO OPEN THE ZIP ARCHIVE.'];
            }
        } else {
            $_SESSION['popup'] = ['error' => 'NO ZIP FILE SELECTED FOR EXTRACTION.'];
        }
    } elseif (isset($_POST['dozip'])) {
        $targetDir = !empty($_POST['zippath']) ? $_POST['zippath'] : '.';
        if (!is_dir($targetDir)) {
            $_SESSION['popup'] = ['error' => 'ZIP DIRECTORY DOES NOT EXIST.'];
        } else {
            $realTargetDir = realpath($targetDir);
            $dirName = basename($realTargetDir);
            $zipFilename = $realTargetDir . DIRECTORY_SEPARATOR . $dirName . ".zip";
            $zip = new ZipArchive();
            if ($zip->open($zipFilename, ZipArchive::CREATE) === TRUE) {
                function addFolderToZip($dir, $zip, $baseLength, $zipFilename) {
                    $files = scandir($dir);
                    foreach ($files as $file) {
                        if ($file == '.' || $file == '..') continue;
                        $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                        if ($filePath == $zipFilename) continue;
                        $localPath = substr($filePath, $baseLength + 1);
                        if (is_dir($filePath)) {
                            $zip->addEmptyDir($localPath);
                            addFolderToZip($filePath, $zip, $baseLength, $zipFilename);
                        } else {
                            $zip->addFile($filePath, $localPath);
                        }
                    }
                }
                $baseLength = strlen($realTargetDir);
                addFolderToZip($realTargetDir, $zip, $baseLength, $zipFilename);
                $zip->close();
                $_SESSION['popup'] = ['location' => $zipFilename];
            } else {
                $_SESSION['popup'] = ['error' => 'FAILED TO CREATE ZIP ARCHIVE.'];
            }
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<head>
  <title>GRAYBYTE PHP UNZIPPER</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Rubik+Vinyl&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Play&display=swap" rel="stylesheet">
  <style type="text/css">
    body {
      font-family: 'Play', sans-serif;
      text-align: center;
      background-color: #000;
      color: #fff;
      text-transform: uppercase;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    h1 {
      font-family: 'Rubik Vinyl', cursive;
      color: green;
      margin-bottom: 5px;
    }
    fieldset {
      width: 90%;
      padding: 4px;
      margin: 0 5%;
      border: 2px solid #555;
      text-align: center;
      background-color: #111;
      box-sizing: border-box;
      align-self: stretch;
    }
    .form-field-label {
      display: block;
      text-align: left;
      margin: 5px auto;
      width: 90%;
      max-width: 400px;
    }
    .form-field {
      width: 90%;
      max-width: 400px;
      padding: 10px;
      border: 1px solid #777;
      background-color: #222;
      color: #fff;
      box-sizing: border-box;
      margin: 0 auto;
    }
    .select {
      padding: 10px;
      font-size: 110%;
      width: 90%;
      max-width: 400px;
    }
    .submit {
      background-color: green;
      border: none;
      color: #fff;
      font-size: 15px;
      padding: 12px 24px;
      margin: 5px 0;
      text-decoration: none;
      cursor: pointer;
    }
    .submit:hover {
      background-color: #005500;
    }
    .popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0.9);
      width: auto;
      max-width: 90%;
      background-color: #111;
      border: 2px solid #555;
      color: white;
      text-align: center;
      padding: 20px;
      z-index: 1000;
      box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
      display: none;
      opacity: 0;
      animation: fadeIn 0.5s ease-in-out, slideUp 0.5s ease-in-out;
      animation-fill-mode: forwards;
      white-space: pre-wrap;
    }
    .popup button {
      background-color: green;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      margin-top: 10px;
    }
    .popup button:hover {
      background-color: #005500;
    }
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 999;
      display: none;
      opacity: 0;
      animation: fadeIn 0.5s ease-in-out;
      animation-fill-mode: forwards;
    }
    .popup .original-case {
      text-transform: none;
    }
    @keyframes fadeIn {
      0% { opacity: 0; }
      100% { opacity: 1; }
    }
    @keyframes slideUp {
      0% { transform: translate(-50%, -60%) scale(0.9); }
      100% { transform: translate(-50%, -50%) scale(1); }
    }
    form {
      display: block;
      width: 90%;
      margin: 0 5%;
      align-self: stretch;
      unicode-bidi: isolate;
    }
  </style>
</head>
<body>
<?php if (isset($_SESSION['popup'])): ?>
  <div class="overlay" id="overlay"></div>
  <div class="popup" id="popup">
    <?php if (isset($_SESSION['popup']['location'])): ?>
      <p>EXTRACTED/CREATED AT: <span class="original-case"><?php echo $_SESSION['popup']['location']; ?></span></p>
    <?php else: ?>
      <p><?php echo $_SESSION['popup']['error']; ?></p>
    <?php endif; ?>
    <button onclick="closePopup()">OK</button>
  </div>
  <?php unset($_SESSION['popup']); ?>
  <script>
    document.getElementById('popup').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
    function closePopup() {
      document.getElementById('popup').style.animation = 'fadeOut 0.5s ease-in-out';
      document.getElementById('overlay').style.animation = 'fadeOut 0.5s ease-in-out';
      setTimeout(() => {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
      }, 500);
    }
  </script>
<?php endif; ?>
<form action="" method="POST">
  <fieldset>
    <h1>GRAYBYTE PHP UNZIPPER</h1>
    <label class="form-field-label" for="zipfile"><br>SELECT YOUR .ZIP OR .RAR ARCHIVE OR .GZ FILE :</label>
    <select name="zipfile" size="1" class="select">
      <?php
      $dir = '.';
      $zipfiles = array_filter(scandir($dir), function($file) use ($dir) {
        return is_file("$dir/$file") && preg_match('/\.(zip|rar|gz)$/i', $file);
      });
      if ($zipfiles) {
        foreach ($zipfiles as $zip) {
          echo "<option>$zip</option>";
        }
      } else {
        echo "<option>NO ZIP FOUND IN CURRENT DIR</option>";
      }
      ?>
    </select>
    <label class="form-field-label" for="extpath"><br>WHERE TO EXTRACT (OPTIONAL):</label>
    <input type="text" name="extpath" class="form-field" placeholder="Leave it blank to use current directory" />
    <p class="info">Provide a directory path (e.g., "myfolder") to extract files there. By default, files will be extracted to the current directory if no path is specified.</p>
    <input type="submit" name="dounzip" class="submit" value="CLICK TO UNZIP"/>
  </fieldset>
  <fieldset>
    <h1>GRAYBYTE PHP ZIPPER</h1>
    <label class="form-field-label" for="zippath"><br>WHERE TO MAKE ZIP (OPTIONAL):</label>
    <input type="text" name="zippath" class="form-field" placeholder="Leave it blank to use current directory" />
    <p class="info">Provide a directory path (e.g., "myfolder") to zip files there. By default, files will be created in the current directory if no path is specified.</p>
    <input type="submit" name="dozip" class="submit" value="CLICK TO ZIP"/>
  </fieldset>
</form>
<p class="maintainer">PHP UNZIPPER MAINTAINED BY <a href="https://t.me/rex_cc" target="_blank">GRAYBYTE</a> | VERSION- 1.0.2</p>
</body>
</html>
