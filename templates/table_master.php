<h1> Liste des pages </h1>
    <table class="table">
        <?php foreach($data['masterTable'] as $tableElement) { ?>
            <tr class="active">
                <td class="info">
                    <strong><?php echo $tableElement['errCount']; ?></strong>
                </td>
                <td>
                    <a href="<?php echo $tableElement['file']; ?>" target="_blank"><?php echo $tableElement['file']; ?></a>
                </td>
            </tr>
        <?php } ?>
    </table>

