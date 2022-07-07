<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of article
 *
 * @author User
 */
class Articles
{
    private $dir = "./articles/";
    
    public function getLenght()
    {
        if(!is_dir($this->dir))
            return 0;
        $t = scandir($this->dir);
        foreach ($t as $key => $value)
        {
            if(!preg_match("/^\d*-\d{2}-\d{2} \d*$/", $value))
            {
                unset($t[$key]);
            }
        }
        $t = array_values($t);
        return count($t);
    }

    public function getArticles($limit, $offset)
    {
        $result = array();
        if(is_nan($limit) || is_nan($offset) || !is_dir($this->dir))
            return $result;
        $t = scandir($this->dir, SCANDIR_SORT_DESCENDING);
        foreach ($t as $key => $value)
        {
            if(!preg_match("/^\d*-\d{2}-\d{2} \d*$/", $value))
            {
                unset($t[$key]);
            }
        }
        $t = array_values($t);
        $this->sort($t);
        for($i=$offset; $i<count($t) && $i<$offset+$limit; $i++)
        {
            $path = $this->dir."/".$t[$i]."/";
            if(is_dir($path))
            {
                if(file_exists($path."/article.txt"))
                {
                    $file = fopen($path."/article.txt", "r");
                    $header = rtrim(fgets($file));
                    $author = rtrim(fgets($file));
                    $date = rtrim(fgets($file));
                    $image =  rtrim(fgets($file));
                    if($image != "")
                        $image = $image;
                    $content = "";
                    while (!feof($file))
                    {
                        $content .= fgets($file);
                    }
                    fclose($file);
                    array_push($result, array("header" => $header, "author" => $author, "date" => $date, "content" => $content, "image" => $image, "id" => $t[$i]));
                }
            }
        }
        return $result;
    }
    
    public function getArticle($id)
    {
        $result = array();
        if(is_dir($this->dir))
        {
            $t = scandir($this->dir);
            foreach ($t as $key => $value)
            {
                if(!preg_match("/^\d*-\d{2}-\d{2} \d*$/", $value))
                {
                    unset($t[$key]);
                }
            }
            $t = array_values($t);
            for($i=0; $i<count($t); $i++)
            {
                if($t[$i] == $id)
                {
                    $path = $this->dir."/".$t[$i]."/";
                    if(is_dir($path))
                    {
                        if(file_exists($path."/article.txt"))
                        {
                            $file = fopen($path."/article.txt", "r");
                            $header = rtrim(fgets($file));
                            $author = rtrim(fgets($file));
                            $date = rtrim(fgets($file));
                            $image =  rtrim(fgets($file));
                            if($image != "")
                                $image = $image;
                            $content = "";
                            while (!feof($file))
                            {
                                $content .= fgets($file);
                            }
                            fclose($file);
                            $result = array("header" => $header, "author" => $author, "date" => $date, "content" => $content, "image" => $image, "id" => $t[$i]);
                            break;
                        }
                    }
                }
            }
        }
        return $result;
    }
    
    public function addArticle($header, $author, $date, $content, $image, $id=NULL)
    {
        if($date == "")
        {
            $date = date("Y-m-d");
        }    
        if($id == NULL)
        {
            $id = $date." ".rand();
        }
        $path = $this->dir."/".$id."/";
        if(!is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        $file = fopen($path."article.txt", "w");
        fwrite($file, $header.PHP_EOL);
        fwrite($file, $author.PHP_EOL);
        fwrite($file, $date.PHP_EOL);
        if(isset($image['name']) && $image['name'] != "")
        {
            $t = scandir($path);
            foreach ($t as $value) {
                if(is_file($path.$value))
                {
                    if($value != "article.txt")
                    {
                        unlink ($path."/".$value);
                    }
                }
            }
            fwrite($file, $path.$image['name'].PHP_EOL);
            move_uploaded_file($image['tmp_name'],$path.$image['name']);
        }
        elseif(is_string($image))
        {
            fwrite($file, $image.PHP_EOL);
        }
        else
        {
            $t = scandir($path);
            foreach ($t as $value) {
                if(is_file($path.$value))
                {
                    if($value != "article.txt")
                    {
                        unlink ($path."/".$value);
                    }
                }
            }
            fwrite($file, PHP_EOL);
        }   
        fwrite($file, $content);
        fclose($file);
    }
    
    public function deleteArticle($id)
    {
        if(is_dir($this->dir.$id))
            $this->rrmdir($this->dir.$id);
    }
    
    private function rrmdir($src)
    {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)))
        {
            if (( $file != '.' ) && ( $file != '..' ))
            {
                $full = $src . '/' . $file;
                if ( is_dir($full) )
                {
                    rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
    
    public function sort(&$t1)
    {
        usort($t1, function ($a, $b)
        {
            $t2 = array($a, $b);
            $tmp = array();
            for($i=0;$i<count($t2);$i++)
            {
                $path = $this->dir."/".$t2[$i]."/";
                if(is_dir($path))
                {
                    if(file_exists($path."/article.txt"))
                    {
                        $file = fopen($path."/article.txt", "r");
                        fgets($file);
                        fgets($file);
                        $date = rtrim(fgets($file));
                        array_push($tmp, $date);
                        fclose($file);
                    }
                }
            }
            $result = 0;
            if(count($tmp) == 2)
            {
                if($tmp[0] < $tmp[1])
                    $result = -1;
                if($tmp[1] > $tmp[0])
                    $result = 1;
            }
            return $result;
        });
    }
}