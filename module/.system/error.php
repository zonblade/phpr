<?php

use phpr\apps\run as apps;

$_DIR = ROOT_FOLDER;
function test(array $input1, array $input2): bool
{
    $result = array_intersect($input1, $input2);
    $result = count($result) > 0;
 
    return $result;
}
$_NEWMSG   = str_replace($_DIR, '[ROOT::PHPR]', $e->getMessage());
$_FILE     = str_replace($_DIR, '[ROOT::PHPR]', $e->getFile());
$_LINE     = str_replace($_DIR, '[ROOT::PHPR]', $e->getLine());
$_FILEN    = $e->getFile();
$statmsg   = '';
$_STSMG    = file($_FILEN);
$_STMGG    = count($_STSMG);

$_EXCLUDE = ['index.php', 'module/urls.php', '.system'];
$_EXPLOSE = explode('/', $_FILEN);
$_EXCLUDA = ['index.php', 'module/urls.php', '.system'];
$_EXPLOSA = explode('/', $_FILEN);
if (!test($_EXCLUDE, $_EXPLOSE)) {
    $linefile  = "#File:\n<i>" . str_replace($_DIR, '</i>[ROOT::PHPR]<i>', $_FILE) . "</i>\n\n";
    if ($_LINE < 3) {
        $statmsg     .= ($_LINE) .  "| #this line\n>> Possible Affected ?\n"
            . "\n" . trim($_STSMG[$_LINE - 1])
            . "\n>> end !!\n"
            . ($_LINE + 1) . '|  ' . trim($_STSMG[$_LINE + 0]) . "\n"
            . ($_LINE + 2) . '|  ' . trim($_STSMG[$_LINE + 1]) . "\n";
    } elseif ($_LINE > $_STMGG - 1) {
        $statmsg     .= ($_LINE - 2) . '|  ' . trim($_STSMG[$_LINE - 3]) . "\n"
            . ($_LINE - 1) . '|  ' . trim($_STSMG[$_LINE - 2]) . "\n"
            . ($_LINE) .  "| #this line\n>> Possible Affected ?"
            . "\n" . trim($_STSMG[$_LINE - 1])
            . "\n>> end !!\n";
    } else {
        $statmsg     .= ($_LINE - 2) . '|  ' . trim($_STSMG[$_LINE - 3]) . "\n"
            . ($_LINE - 1) . '|  ' . trim($_STSMG[$_LINE - 2]) . "\n"
            . ($_LINE) .  "| #this line\n>> Possible Affected ?"
            . "\n" . trim($_STSMG[$_LINE - 1])
            . "\n>> end !!\n"
            . ($_LINE + 1) . '|  ' . trim($_STSMG[$_LINE + 0]) . "\n"
            . ($_LINE + 2) . '|  ' . trim($_STSMG[$_LINE + 1]) . "\n";
    }
    $statmsg = '<pre data-line="" ><code class="codes language-php">' . $linefile . $statmsg . '</code></pre>';
}else{
    $statmsg = "<pre data-line=\"\" ><code class=\"codes language-php\">This is a System Message\nPlease take a look at the traceback!</code></pre>";
}
$_TRACE    = $e->getTrace();
try {
    $_SEVERITY = str_repeat('&#11088;', (int)$e->getSeverity());
} catch (\Throwable $n) {
    $_SEVERITY = str_repeat('&#11088;', (int)2);
}
$lines = '';
$lined = '';
foreach ($_TRACE as $_A => $_B) {
    if (isset($_B['file'])) {
        $_B = (object)$_B;
        $_INF = file($_B->file);
        $_INC = count($_INF);
        $_EXCLUDE = ['index.php', 'module/urls.php', '.system'];
        $_EXPLOSE = explode('/', $_B->file);
        if (!test($_EXCLUDE, $_EXPLOSE)) {
            if (isset($_INF[$_B->line])) {
                if ($_INC > 0) {
                    $_INC = $_INC - 1;
                }
                $lined = "#File:\n<i>" . str_replace($_DIR, '</i>[ROOT::PHPR]<i>', $_B->file) . "</i>\n\n";
                if ($_B->line < 3) {
                    $lined     .= ($_B->line) .  "| #this line\n>> Possible Affected ?\n"
                        . "\n" . trim($_INF[$_B->line - 1])
                        . "\n>> end !!\n"
                        . ($_B->line + 1) . '|  ' . trim($_INF[$_B->line + 0]) . "\n"
                        . ($_B->line + 2) . '|  ' . trim($_INF[$_B->line + 1]) . "\n";
                } elseif ($_B->line > $_INC - 1) {
                    $lined     .= ($_B->line - 2) . '|  ' . trim($_INF[$_B->line - 3]) . "\n"
                        . ($_B->line - 1) . '|  ' . trim($_INF[$_B->line - 2]) . "\n"
                        . ($_B->line) .  "| #this line\n>> Possible Affected ?"
                        . "\n" . trim($_INF[$_B->line - 1])
                        . "\n>> end !!\n";
                } else {
                    $lined     .= ($_B->line - 2) . '|  ' . trim($_INF[$_B->line - 3]) . "\n"
                        . ($_B->line - 1) . '|  ' . trim($_INF[$_B->line - 2]) . "\n"
                        . ($_B->line) .  "| #this line\n>> Possible Affected ?"
                        . "\n" . trim($_INF[$_B->line - 1])
                        . "\n>> end !!\n"
                        . ($_B->line + 1) . '|  ' . trim($_INF[$_B->line + 0]) . "\n"
                        . ($_B->line + 2) . '|  ' . trim($_INF[$_B->line + 1]) . "\n";
                }
                $lines .= '<pre data-line="" ><code class="codes language-php">' . $lined . '</code></pre>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Code Error!!</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.9, user-scalable=no">
    <link rel="stylesheet" href="<?= apps\URI_GLOB('/static/prism.css') ?>">
    <script src="<?= apps\URI_GLOB('/static/prism.js') ?>"></script>
    <style>
        .parent {
            min-height: 80vh;
            display: flex;
            justify-content: center;
        }

        .child {
            width: 100%;
            max-width: 800px;
        }

        .theline {
            margin-top: 2px;
            margin-bottom: 2px;
            border-bottom: 1px solid black;
        }

        .text {
            margin-top: 5px !important;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid rgba(0, 0, 0, 0.4);
            word-break: break-all;
        }

        h4 {
            margin-top: 25px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body class="parent">
    <div class="child">
        <h3>Severity: <?= $_SEVERITY ?></h3>
        <h4>Status Message:</h4>
        <p class="text">
            <?= $_NEWMSG ?>
        </p>
        <h4>Location From Status Message:</h4>
        <?= $statmsg ?>
        <h4>Location Traceback:</h4>
        <?= $lines ?>
    </div>
    <script></script>
</body>

</html>