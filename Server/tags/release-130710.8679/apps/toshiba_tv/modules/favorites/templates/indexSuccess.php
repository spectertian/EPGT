<h1>Favoritess List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>User</th>
      <th>Pid</th>
      <th>Type</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($favoritess as $favorites): ?>
    <tr>
      <td><a href="<?php echo url_for('favorites/edit?id='.$favorites->getId()) ?>"><?php echo $favorites->getId() ?></a></td>
      <td><?php echo $favorites->getUserId() ?></td>
      <td><?php echo $favorites->getPid() ?></td>
      <td><?php echo $favorites->getType() ?></td>
      <td><?php echo $favorites->getCreatedAt() ?></td>
      <td><?php echo $favorites->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('favorites/new') ?>">New</a>
