<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>HTML5 Validation</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <div class="row">
        <?php
            $error = 0; $info = 0;
            foreach($data['jsonIterator']['messages'] as $msg) {
                if($msg['type'] == 'error')
                    $error++;

                if($msg['type'] == 'info')
                    $info++;
            }
        ?>
        <h1><?php echo $error; ?> erreurs et <?php echo $info; ?> infos dans cette page</h1>
        <div class="panel-body">
           URL: <a href="<?php echo $data['jsonIterator']['url']; ?>" target="_blank"><?php echo $data['jsonIterator']['url']; ?></a>
        </div>
        <dl class="dl-horizontal">
            <?php
                foreach($data['jsonIterator']['messages'] as $error)
                {
                    echo template('result_item_error.php', $error);
                }
            ?>
        </dl>
    </div>
</body>
</html>
