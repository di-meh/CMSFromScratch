<?php
header("Content-Type: text/xml; charset=utf-8");
$connect = mysqli_connect("database", "root", "password", "liblydocker");

$query1 = "SELECT slug FROM lbly_page";
$query2 = "SELECT slug FROM lbly_article";
//$query3 = "SELECT slug FROM lbly_books";

$result1 = mysqli_query($connect, $query1);
$result2 = mysqli_query($connect, $query2);
//$result3 = mysqli_query($connect, $query3);

$base_url = "http://localhost/";

print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . PHP_EOL;

echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;


while ($row = mysqli_fetch_array($result1)){
    print '<url>' . PHP_EOL;
    print '<loc>' . $base_url . $row["slug"] . '/</loc>' . PHP_EOL;
    //echo '<changefreq>daily</changefreq>' . PHP_EOL;
    print '</url>' . PHP_EOL;
}

while ($row = mysqli_fetch_array($result2)){
    print '<url>' . PHP_EOL;
    print '<loc>' . $base_url . $row["slug"] . '/</loc>' . PHP_EOL;
    //echo '<changefreq>daily</changefreq>' . PHP_EOL;
    print '</url>' . PHP_EOL;
}

/*
while ($row = mysqli_fetch_array($result3)){
    print '<url>' . PHP_EOL;
    print '<loc>' . $base_url . $row["slug"] . '/</loc>' . PHP_EOL;
    //echo '<changefreq>daily</changefreq>' . PHP_EOL;
    print '</url>' . PHP_EOL;
}
*/

print '</urlset>' . PHP_EOL;