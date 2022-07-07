<?php            
    include 'articles.php';
    include 'page.php';
    $articles = new Articles();
    $limit = 2;
    $page = new Page($limit, $articles->getLenght());
    if(isset($_GET['page']))
    {
        $page = new Page($limit, $articles->getLenght(), $_GET['page']);
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
        <link rel="stylesheet" href="css/main.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <title>NEWS SYSTEM</title>
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
                </ul>
            </nav>
        </header>
        
        <main class="content">
            <?php
                if(isset($_GET['id']))
                {
                    echo "<div class='articles'>";
                    $article = $articles->getArticle($_GET['id']);
                    echo "<article class='article2'>";
                    echo "<div class='img-box2'>";
                    if(isset($article['image'] )&& $article['image'] != "")
                    {
                        echo "<img src='".htmlspecialchars($article['image'])."'>";
                    }
                    echo "</div>";
                    echo "<div class='articleContent'>";
                    if(isset($article['header']))
                    {
                        echo "<p class='title'>";
                        echo htmlspecialchars($article['header']);
                        echo "</p>";
                    }
                    if(isset($article['author']))
                        echo "<span style='margin-right: 20px;'>".$article['author']."</span>";
                    if(isset($article['date']))
                        echo "<span>".$article['date']."</span>";
                    echo "<div>".$article['content']."</div>";
                    echo "</div>";
                    echo "</article>";
                    echo "</div>";
                }
                else
                {
                    echo "<div class='articles'>";
                    $t = $articles->getArticles($page->getLimit(), $page->getOffset());
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
                            
                            echo "</div>";
                            echo "</article>";
                        }
                        
                        echo "<div class='pages'>";
                        echo "<div>";
                        if($page->getPage() > 0)
                        {
                            echo "<a href='./news.php?page=". urldecode($page->getPage()-1)."'>Poprzednia strona</a>";
                        }
                        echo " ".($page->getPage()+1)." ";
                        if($page->getLimit()+$page->getOffset() < $articles->getLenght())
                        {
                            echo "<a href='./news.php?page=". urldecode($page->getPage()+1)."'>Następna strona</a>";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                    else
                    {
                        echo "<div class='article1'>";
                        echo "Nie ma żadnych artykułów";
                        echo "</div>";
                    }
                    echo "</div>";
                }
            ?>
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