<?php
    include 'user.php';
    $user = new User();
    if($user->isLoggedIn())
    {
        include 'articles.php';
        $articles = new Articles();
        $article = array();          
        if(isset($_POST['add']) && $_POST['add'] == TRUE)
        {
            if(isset($_POST['header']) && isset($_POST['author']) && isset($_POST['date']) && isset($_POST['content']) && isset($_FILES['image']))
            {
                $articles->addArticle($_POST['header'], $_POST['author'], $_POST['date'], $_POST['content'], $_FILES['image']);
                header('Location: admin.php');
            }
        }
        else if(isset($_POST['edit']) && $_POST['edit'] == TRUE)
        {
            if(isset($_POST['header']) && isset($_POST['author']) && isset($_POST['date']) && isset($_POST['content']))
            {
                $image;
                if(isset($_FILES['image']))
                {
                    $image = $_FILES['image'];
                }
                else if(isset($_POST['image']))
                {
                    $image = $_POST['image'];
                }
                $articles->addArticle($_POST['header'], $_POST['author'], $_POST['date'], $_POST['content'], $image, $_POST['id']);
                header('Location: admin.php');
            }
        }
        else if(isset($_GET['id'])) 
        {
            $article = $articles->getArticle($_GET['id']);
        }
    }
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dodaj artykuł</title>
        <link rel="stylesheet" href="css/main.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script>
            function deleteImage()
            {
                var img1 = document.getElementById("img1");
                var img2 = document.getElementById("img2");
                var imageCell = document.getElementById("imageCell");
                var deleteImageBtn = document.getElementById("deleteImageBtn");
                if(img1 != null && img2 != null && imageCell != null && deleteImageBtn != null)
                {
                    img1.remove();
                    img2.remove();
                    deleteImageBtn.remove();
                    var fileInput = document.createElement("input");
                    fileInput.type = "file";
                    fileInput.name = "image";
                    fileInput.id = "image";
                    imageCell.appendChild(fileInput);
                }
            }
        </script>
    </head>
    <body>
        <header>
            <nav class="menu">
                <ul class="menu-icon">
                    <label for="menu-button" class="menu-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </label>
                </ul>
                <input type="checkbox" id="menu-button">
                <ul class="hidden">
                    <li><a href="./news.php">NEWS SYSTEM</a></li>
                    <li><a href="./admin.php">PANEL ADMINISTRATORA</a></li>
                </ul>
                <ul class="hidden">
                    <?php if($user->isLoggedIn() == TRUE):?>
                    <?php echo "<li>ZALOGOWANY UŻYTKOWNIK ".$user->getLogin()."</li>"; ?>
                    <li><a href="./admin.php?logout=true">WYLOGUJ</a></li>
                    <?php endif;?>
                </ul>
            </nav>
        </header>
        <main class="content">
            <?php if($user->isLoggedIn() == TRUE): ?>
                <div class="articles">
                    <article class="page">
                            <p class="pageTitle">DODAJ ARTYKUŁ</p>
                            <form action="add.php" method="POST" enctype="multipart/form-data">
                                <table>
                                    <tr>
                                        <td><label for="header">NAGŁÓWEK:</label></td>
                                        <td><input type="text" name="header" id="header" <?php if(isset($article['header'])){echo "value='".$article['header']."'";}; ?> ></td>
                                    </tr>
                                    <tr>
                                        <td><label for="author">AUTOR:</label></td>
                                        <td><input type="text" name="author" id="author" <?php if(isset($article['author'])){echo "value='".$article['author']."'";}; ?> ></td>
                                    </tr>
                                    <tr>
                                        <td><label for="date">DATA:</label></td>
                                        <td><input type="date" name="date" id="date" <?php if(isset($article['date'])){echo "value='".$article['date']."'";}; ?> ></td>
                                    </tr>
                                    <tr>
                                        <td><label for="content">TREŚĆ:</label></td>
                                        <td><textarea name="content" id="content"><?php if(isset($article['content'])){ echo $article['content'];}; ?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td><label for="image">ZDJĘCIE:</label></td>
                                        <td id="imageCell">
                                        <?php if(isset($article['id']) && $article['image'] != ""):
                                            echo "<div class='img-box'><img id='img1' src='".$article['image']."'></div>";
                                            echo "<input type='hidden' id='img2' name='image' value='".$article['image']."'>";
                                            echo "<br>";
                                            echo "<input type='button' id='deleteImageBtn' value='USUŃ ZDJĘCIE' onclick='deleteImage();'>";
                                            echo "</td>";
                                        ?>
                                        <?php else: ?>
                                        <input type="file" name="image" id="image">
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                        <?php if(isset($article['id'])): ?>
                                        <input type="hidden" name="edit" value="TRUE">
                                        <input type="hidden" name="id" <?php echo "value='".$article['id']."'";?> >
                                        <input type="submit" value="EDYTUJ ARTYKUŁ"></td>
                                        <?php else: ?>
                                        <input type="hidden" name="add" value="TRUE">
                                        <input type="submit" value="DODAJ ARTYKUŁ">
                                        <?php endif; ?>
                                        <td>
                                    </tr>
                                </table>
                            </form>
                        </article>
                </div>
            <?php else: ?>
                <a href="admin.php">Zaloguj się aby dodać artykuł.</a>
            <?php endif; ?>
        </main>
        <footer>
            <nav class="menu">
                <ul class='info'>
                    <li>Paweł Trojański klasa 3TI grupa 2</li>
                </ul>
            </nav>
        </footer>
    </body>
</html>