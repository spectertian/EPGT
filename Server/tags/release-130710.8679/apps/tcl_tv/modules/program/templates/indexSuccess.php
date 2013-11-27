<?php var_dump($programs); die() ?>
<?php foreach($programs as $program): ?>
<?php echo $program->getName() . "<br />"; ?>
<?php endforeach; ?>