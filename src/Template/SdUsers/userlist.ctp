<title>Users List</title>

<div class="card text-center w-75 my-3 mx-auto">
  <div class="card-header">
    <h3><?php echo __("Users List")?></h3>
  </div>
  <div class="card-body">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name </th>
          <th scope="col">E-mail</th>
          <th scope="col">Role</th>
          <th scope="col">Company</th>
          <th scope="col">Created At</th>
          <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($sdUsers as $sdUser): ?>
        <tr>
          <th scope="row"><?= $this->Number->format($sdUser->id) ?></th>
          <td><?= h($sdUser->firstname) ?>&nbsp;<?= h($sdUser->lastname) ?></td>
          <td><?= h($sdUser->email) ?></td>
          <td><?= h($sdUser->sd_role->role_name) ?></td>
          <td><?= h($sdUser->sd_company->company_name) ?></td>
          <td><?= h($sdUser->created_dt) ?></td>
          <td class="actions">
              <?= $this->Html->link(__('Edit'),
                ['action' => 'edituser', $sdUser->id],
                ['class' => 'btn btn-outline-success w-50']
              ) ?>
              <?= $this->Form->postLink(__('Delete'),
                ['action' => 'delete', $sdUser->id],
                ['class' => 'btn btn-outline-danger'],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sdUser->id)]
              ) ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>