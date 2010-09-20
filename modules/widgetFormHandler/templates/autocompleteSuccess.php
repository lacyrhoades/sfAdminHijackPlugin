<?php decorate_with(false); ?>
<?php foreach($results as $object): ?>
<?php echo implode('|',$object->getRawValue()).PHP_EOL; ?>
<?php endforeach; ?>
