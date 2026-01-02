<?php
  use BitSynama\Lapis\Lapis;
  
  $csrf_token = Lapis::sessionUtility()->getCsrfToken();
  $current_url = Lapis::requestUtility()->getCurrentUrl();
?>
<?php if (! empty($this)): ?>
  <?php 
    $this->layout('layouts:admin.default', [
      'title' => $title ?? 'List of blog posts',
      'createAction' => $current_url . '/create',
    ]); 
  ?>
<?php endif; ?>

<?php if (isset($records) && !empty($records)): ?>
  <?php $this->insert('partials:admin.pagination', [
      'pagination' => $pagination,
  ]); ?>

  <div class="table-responsive">
    <table class="table-lapis-sm table align-middle">
      <thead>
        <tr>
          <th>Title</th>
          <th>Slug</th>
          <th>Excerpt</th>
          <th>Status</th>
          <th>Published At</th>
          <th>Post Category</th>
          <th>Tags</th>
          <th>Created By</th>
          <th>Updated By</th>
          <th class="lapis-row-action">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($records as $row): ?>
          <tr>
            <td><?= $this->e($row['title']); ?></td>
            <td><?= $this->e($row['slug']); ?></td>
            <td><?= $this->e($row['excerpt']); ?></td>
            <td><?= $row['status']; ?></td>
            <td><?= $row['published_at']; ?></td>
            <td><?= $this->e($row['category']['name']); ?></td>
            <td>
              <?php if (!empty($row['tags'])): ?>
                <?php 
                  $tags = array_map(fn ($tag) => $tag['name'], $row['tags']);
                  echo implode(', ', $tags);
                ?>
              <?php endif; ?>
            </td>
            <td>
              <?php if (!empty($row['created_by'])): ?>
                <?= $row['created_by']['name']; ?>
              <?php endif; ?>
            </td>
            <td>
              <?php if (!empty($row['updated_by'])): ?>
                <?= $row['updated_by']['name']; ?>
              <?php endif; ?>
            </td>

            <td class="lapis-row-action">
              <a href="<?= $current_url ?>/<?= $row['entity_id'] ?>" class="btn btn-sm btn-outline-success mr-1 mb-1">View</a>
              <a href="<?= $current_url ?>/edit/<?= $row['entity_id'] ?>" class="btn btn-sm btn-outline-primary mr-1 mb-1">Edit</a>
              <form method="post" action="<?= $current_url ?>/<?= $row['entity_id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= $this->e($csrf_token) ?>">
                <input type="hidden" name="_method" value="delete">
                <input type="hidden" name="entity_id" value="<?= $row['entity_id'] ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
              </form>&nbsp;
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php $this->insert('partials:admin.pagination', [
      'pagination' => $pagination,
  ]); ?>
<?php else: ?>
  <div class="alert alert-warning" role="alert">
    <span>No records available.</span>
  </div>
<?php endif; ?>
