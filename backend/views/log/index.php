<?php
/* @var $errors arrayObject */

?>

<a href="/admin/log/clear-log">Очистить</a><br>

<?php foreach ($errors as $error): ?>
    file: <?=$error['file']?> <br>
    line: <?=$error['line']?> <br>
    code: <?=$error['code']?> <br>
    ip: <?=$error['ip']?> <br>
    text: <?=$error['text']?> <br> <br>
<?php endforeach; ?>
