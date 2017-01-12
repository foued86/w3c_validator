<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>HTML5 Validation</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="assets/js/jquery-2.2.1.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.bootpag.min.js"></script>
</head>
    <body>
    </body>
</html>

<?php
ini_set('max_execution_time', 4800);
ini_set('memory_limit', '2G');

function template($file, $data)
{
    ob_start();
    include(__DIR__.'/templates/'.$file);
    return ob_get_clean();
}

function getJson($doc="")
{
    //$content = file_get_contents("http://html5.validator.nu/?doc=http://legroupe.laposte.fr/profil/le-groupe-en-bref/l-essentiel&out=json");
    //$doc = "http://legroupe.laposte.fr";

    $content = file_get_contents("http://html5.validator.nu/?doc=".$doc."&out=json");
    $json = json_decode($content, true);

    return $json;
}

function analyseUrl($tabUrl)
{
    $config = parse_ini_file(__DIR__.'/config.ini');
    $home = $config['home'];
    $i = 0;

    foreach ($tabUrl as $url)
    {
        $json = getJson($url);
        if (strcmp($home, $json['url']) === 0) {
            $file = "pages/home.html";
        } else {
            $file = explode('/', $json['url']);
            if (!file_exists("pages/".$file[3].".html")) {
                $file = "pages/".$file[3].".html";
            } else {
                $file = "pages/".$file[3]."_".$i++.".html";
            }
        }

        file_put_contents($file, template('result_item.php', array(
            'jsonIterator' => $json
        )));

        $tableBuilder[] = array(
            'file' => $file,
            'errCount' => count($json['messages']),
            'url' => $json['url']
        );
    }

    return $tableBuilder;
}

function buildTableMaster($masterTable)
{
    usort($masterTable, function ($left, $right) {
        return ($left['errCount'] > $right['errCount']) ? -1 : 1;
    });

    return $masterTable;
}

function clearFolder($folder="pages")
{
    $handle = @opendir($folder);
    if( !$handle ) return;
    while( $file = readdir($handle) )
    {
        @unlink($folder . "/" . $file);
    }
    closedir($handle);
}

function parseXml($start=0, $end)
{
    $config = parse_ini_file(__DIR__.'/config.ini');
    $arrayFinal = array();

    //$urlSitemap = "http://legroupe.laposte.fr/layout/set/googlesitemap/content/view/googlesitemaps/13430";
    $urlSitemap = str_replace('__DIR__', __DIR__, $config['sitemap']);
    $file = file_get_contents($urlSitemap);
    $xml = simplexml_load_string($file, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);

    $arrayUrl = array_map(function($item)
    {
        return $item['loc'];
    }, $array['url']);

    if(!isset($end)) $end = count($arrayUrl);
    for($i=$start; $i<$end; $i++) {
        array_push($arrayFinal, $arrayUrl[$i]);
    }

    echo "Liste des fichiers générée...\n".PHP_EOL;

    return $arrayFinal;
}

function generateCSV($tab)
{
    $path = 'result.csv';
    $delimiter = ';';

    $csvFile = fopen($path, 'a+');

    foreach($tab as $line) {
        fputcsv($csvFile, $line, $delimiter);
    }

    echo "Fichier CSV généré avec succés...\n".PHP_EOL;

    fclose($csvFile);
}

/*$tabUrl  = array( 0 => "http://legroupe.laposte.fr",
    1 => "http://legroupe.laposte.fr/profil/le-groupe-en-bref/l-essentiel",
    2 => "http://legroupe.laposte.fr/profil/la-gouvernance/l-equipe-de-direction",
    3 => "http://legroupe.laposte.fr/activites/un-groupe-multiactivite"
);*/
/******************* Main *********************/
echo "Début du script...\n".PHP_EOL;

$tabUrl = parseXml(0, 2);

clearFolder();
$masterTable = analyseUrl($tabUrl);
sleep(10);
generateCSV($masterTable);

echo template('table_master.php', array(
        'masterTable' => buildTableMaster($masterTable)
    ));

echo "Fin du script\n".PHP_EOL;
?>
