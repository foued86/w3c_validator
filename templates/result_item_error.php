<dt>
    <?php if (strcmp($data['type'], "error") === 0) { ?>
        <strong class="bg-danger"><?php echo strtoupper($data['type']); ?></strong>
    <?php } else { ?>
        <strong class="bg-warning"><?php echo strtoupper($data['type']); ?></strong>
    <?php } ?>
</dt>
<dd class="bg-info">
    <p><?php echo $data['message']; ?></p>
    <p>At line <?php echo $data['lastLine']; ?>, column <?php echo $data['lastColumn']; ?></p>
    <?php if (isset($data['extract'])) { ?>
        <pre><?php echo htmlentities(trim($data['extract'])); ?></pre>
    <?php } ?>
</dd>
<br>