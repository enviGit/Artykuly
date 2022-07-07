 <?php
    include 'user.php';
    $user = new User();
    if(isset($_POST['login']) && isset($_POST['password']))
    {
        $user = new User($_POST['login'], $_POST['password']);
        if($user->isLoggedIn() == TRUE)
        {
            header('Location: admin.php');   
        }
    }
    if($user->isLoggedIn() == TRUE)
    {
        if(isset($_GET['logout']) && $_GET['logout'] == TRUE)
        {
            $user->logOut();
        }
    }

    include 'articles.php';
    include 'page.php';
    $articles = new Articles();   
    if($user->isLoggedIn() == TRUE)
    {
        if(isset($_GET['delete']) && $_GET['delete'] == TRUE)
        {
           $articles->deleteArticle($_GET['id']);
        }
        $limit = 2;
        $page = new Page($limit, $articles->getLenght());
        if(isset($_GET['page']))
        {
            $page = new Page($limit, $articles->getLenght(), $_GET['page']);
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
        <title>Panel administratora</title>
        <link rel="stylesheet" href="css/main.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <?php if($user->isLoggedIn() == TRUE):?>
                    <li><a href="./add.php">DODAJ ARTYKUŁ</a></li>
                    <?php endif;?>
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
            <?php if($user->isLoggedIn() == FALSE): ?>
            <div class="articles">
                <article class="page loginPage">
                        <p class="pageTitle">LOGOWANIE</p>
                        <form action="./admin.php" method="POST">
                            <table>
                                <tr>
                                    <td><label for="login">LOGIN: </label></td>
                                    <td><input type="text" name="login" id="login" placeholder="login"></td>
                                </tr>
                                <tr>
                                    <td><label for="password">HASŁO: </label></td>
                                    <td><input type="password" name="password" id="password" placeholder="hasło"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="submit" value="ZALOGUJ" id="logInbutton"></td>
                                </tr>
                            </table>
                        </form>
                        <?php 
                        if(isset($_POST['login']) && isset($_POST['password']))
                        {
                            echo "Nieprawidłowy login lub hasło.";
                        };?>
                </article>
            </div>
            <?php else:
                $t = $articles->getArticles($page->getLimit(), $page->getOffset());
                
                echo "<div class='articles'>";
                if(count($t) > 0)
                {
                    foreach ($t as $article)
                    {
                        echo "<article class='article1'>";
                        
                        echo "<div class='img-box'>";
                        if(isset($article['image'] )&& $article['image'] != "")
                        {
                            echo "<a class='toArticle' target='_blank' href='./news.php?id=".urlencode($article['id'])."'>";
                            echo "<img src='".htmlspecialchars($article['image'])."'>";
                            echo "</a>";
                        }
                        echo "</div>";
                        echo "<div class='articleHeader'>";
                        if(isset($article['header']))
                        {
                            echo "<p class='title'>";
                            echo "<a class='toArticle' target='_blank' href='./news.php?id=".urlencode($article['id'])."'>";
                            echo htmlspecialchars($article['header']);
                            echo "</a>";
                            echo "</p>";
                        }
                        if(isset($article['author']))
                            echo "<span style='margin-right: 20px;'>".$article['author']."</span>";
                        if(isset($article['date']))
                            echo "<span>".$article['date']."</span>";
                        echo "<div>";
                        echo substr(htmlspecialchars($article['content']), 0, 200)."...";
                        echo "</div>";
                        echo "<form method='GET'>";
                        echo "<input type='hidden' name='id' value='".urldecode($article['id'])."'>";
                        echo "<input type='hidden' name='delete' value='TRUE'>";
                        echo "<input type='submit' value='USUŃ' formaction='./admin.php' onclick='return confirm(\"Czy napewno chcesz usunąć artykuł?\");'>";
                        echo "<input type='submit' value='EDYTUJ' formaction='./add.php'>";
                        echo "</form>";
 
                        echo "</div>";
                        echo "</article>";
                    }
                    echo "<div class='pages'>";
                    echo "<div>";
                    if($page->getPage() > 0)
                    {
                        echo "<a href='./admin.php?page=". urldecode($page->getPage()-1)."'>Poprzednia strona</a>";
                    }
                    echo " ".($page->getPage()+1)." ";
                    if($page->getLimit()+$page->getOffset() < $articles->getLenght())
                    {
                        echo "<a href='./admin.php?page=". urldecode($page->getPage()+1)."'>Następna strona</a>";
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                else
                {
                    echo "<div class='article1'>";
                    echo "Nie ma żadnych artykułów";
                    echo "</div>";
                }
            endif; ?>
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