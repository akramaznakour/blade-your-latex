<?php

require './vendor/autoload.php';

use Jenssegers\Blade\Blade;



define("YAML_EXTENSION", "yaml");
define("JSON_EXTENSION", "json");
define("ACCEPTABLE_FILE_EXTENSIONS", [YAML_EXTENSION, JSON_EXTENSION]);


function getFileExtension($fileFullName)
{
    $sections = explode(".", strtolower($fileFullName));
    return end($sections);
}

function getFileName($fileFullName)
{
    return (explode(".", strtolower($fileFullName)))[0];
}

function isAcceptableFile($fileFullName)
{
    $fileExtension  = getFileExtension($fileFullName);
    return in_array($fileExtension, ACCEPTABLE_FILE_EXTENSIONS);
}


function renderToLatex($blade, $fileFullName)
{
    $fileName  = getFileName($fileFullName);
    $fileExtension  = getFileExtension($fileFullName);
    $filePath = "./input/" . $fileFullName;
    $texOutputPath = "./output/tex/" . $fileName . '.tex';

    $data = null;

    if ($fileExtension === YAML_EXTENSION) {
        $data = yaml_parse(file_get_contents($filePath));
    } elseif ($fileExtension === JSON_EXTENSION) {
        $data = json_decode(file_get_contents($filePath), true);
        echo JSON_EXTENSION;
    } else {
        echo 'it not a supported file type';
    }

    $texString = $blade->make($fileName, ['data' => $data])->render();
    file_put_contents($texOutputPath, $texString);
}

function main()
{
    $blade = new Blade('views', 'cache');
    $blade->addExtension('blade.tex', 'blade');

    $inputFiles = array_filter(scandir("input"), "isAcceptableFile");
    foreach ($inputFiles as $inputFile) renderToLatex($blade, $inputFile);
    // foreach ($inputFiles as $inputFile) exec('"scripts/run.bat" ' . getFileName($inputFile));

}

main();
exit(-1);