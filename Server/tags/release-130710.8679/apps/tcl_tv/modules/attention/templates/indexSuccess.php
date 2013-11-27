<h1>Attentions List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>User</th>
      <th>Pid</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($attentions as $attention): ?>
    <tr>
      <td><a href="<?php echo url_for('attention/edit?id='.$attention->getId()) ?>"><?php echo $attention->getId() ?></a></td>
      <td><?php echo $attention->getUserId() ?></td>
      <td><?php echo $attention->getPid() ?></td>
      <td><?php echo $attention->getCreatedAt() ?></td>
      <td><?php echo $attention->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('attention/new') ?>">New</a>
