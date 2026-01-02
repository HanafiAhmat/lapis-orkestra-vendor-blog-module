<?php declare(strict_types=1); ?>

<?php if (! empty($this)): ?>
  <?php $this->layout('layouts:admin.default', [
      'title' => 'Create New Blog Post',
  ]); ?>
<?php endif; ?>

<div class="container-fluid">
  <form method="post" action="/admin/blog/posts">
    <input type="hidden" name="csrf_token" value="<?= $this->e($csrf_token) ?>">

    <div class="row g-4">      
      <!-- LEFT COLUMN: General Information -->
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white fw-semibold">
            <i class="bi bi-file-text me-1"></i> General Information
          </div>
          <div class="card-body">
            
            <?php if (!empty($fail)): ?>
              <div class="alert alert-danger">
                <?= $this->e($fail) ?>
              </div>
            <?php endif; ?>

            <!-- Title -->
            <div class="mb-3">
              <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
              <input type="text" id="title" name="title" maxlength="196" required 
                     class="form-control <?= !empty($errors['title']) ? 'is-invalid' : '' ?>"
                     value="<?= $this->e($old['title'] ?? '') ?>">
              <?php if (!empty($errors['title'])): ?>
                <div class="invalid-feedback"><?= $this->e($errors['title']) ?></div>
              <?php endif; ?>
            </div>

            <!-- Slug -->
            <div class="mb-3">
              <label for="slug" class="form-label">Slug</label>
              <input type="text" id="slug" name="slug" maxlength="254"
                     class="form-control <?= !empty($errors['slug']) ? 'is-invalid' : '' ?>"
                     value="<?= $this->e($old['slug'] ?? '') ?>">
              <?php if (!empty($errors['slug'])): ?>
                <div class="invalid-feedback"><?= $this->e($errors['slug']) ?></div>
              <?php endif; ?>
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <input type="text" id="description" name="description" maxlength="154"
                     class="form-control <?= !empty($errors['description']) ? 'is-invalid' : '' ?>"
                     value="<?= $this->e($old['description'] ?? '') ?>">
              <?php if (!empty($errors['description'])): ?>
                <div class="invalid-feedback"><?= $this->e($errors['description']) ?></div>
              <?php endif; ?>
            </div>

            <!-- Excerpt -->
            <div class="mb-3">
              <label for="excerpt" class="form-label">Excerpt</label>
              <textarea id="excerpt" name="excerpt" rows="3"
                        class="form-control <?= !empty($errors['excerpt']) ? 'is-invalid' : '' ?>"><?= $this->e($old['excerpt'] ?? '') ?></textarea>
              <?php if (!empty($errors['excerpt'])): ?>
                <div class="invalid-feedback"><?= $this->e($errors['excerpt']) ?></div>
              <?php endif; ?>
            </div>

            <!-- Content (Markdown WYSIWYG) -->
            <div class="mb-3">
              <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
              <textarea id="content" name="content" rows="12"
                        class="form-control <?= !empty($errors['content']) ? 'is-invalid' : '' ?>"><?= $this->e($old['content'] ?? '') ?></textarea>
              <?php if (!empty($errors['content'])): ?>
                <div class="invalid-feedback"><?= $this->e($errors['content']) ?></div>
              <?php endif; ?>
              <div class="form-text">
                Supports **Markdown** syntax. Toolbar available above the editor.
              </div>
            </div>

          </div>
        </div>

        <!-- Actions Row -->
        <div class="mt-4 d-flex justify-content-between">
          <a href="/admin/blog/posts" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Cancel
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Save Changes
          </button>
        </div>
      </div>

      <!-- RIGHT COLUMN: Publishing & Taxonomy -->
      <div class="col-lg-4">
        <!-- Actions Row -->
        <div class="mb-4 justify-content-between d-none d-sm-flex">
          <a href="/admin/blog/posts" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Cancel
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Save Changes
          </button>
        </div>

        <!-- Publishing Options -->
        <div class="card shadow-sm mb-4">
          <div class="card-header fw-semibold">
            <i class="bi bi-calendar-check me-1"></i> Publishing Options
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <select id="status" name="status" required
                      class="form-select <?= !empty($errors['status']) ? 'is-invalid' : '' ?>">
                <option value="">-- Select Status --</option>
                <option value="draft"     <?= ($old['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="published" <?= ($old['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="scheduled" <?= ($old['status'] ?? '') === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                <option value="archived"  <?= ($old['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
              </select>
              <?php if (!empty($errors['status'])): ?>
                <div class="invalid-feedback"><?= $this->e($errors['status']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="published_at" class="form-label">Published At</label>
              <input type="datetime-local" id="published_at" name="published_at"
                     class="form-control"
                     value="<?= $this->e($old['published_at'] ?? '') ?>">
            </div>

            <div class="mb-3">
              <label for="scheduled_at" class="form-label">Scheduled At</label>
              <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                     class="form-control"
                     value="<?= $this->e($old['scheduled_at'] ?? '') ?>">
            </div>

            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="comments_enabled"
                     name="comments_enabled" value="1"
                     <?= empty($old['comments_enabled']) || $old['comments_enabled'] === '1' ? 'checked' : '' ?>>
              <label class="form-check-label" for="comments_enabled">Enable Comments</label>
            </div>
          </div>
        </div>

        <!-- Taxonomy -->
        <div class="card shadow-sm mb-4">
          <div class="card-header fw-semibold">
            <i class="bi bi-tags me-1"></i> Taxonomy
          </div>
          <div class="card-body">
            
            <div class="mb-3">
              <label for="blog_category_id" class="form-label">Category</label>
              <select id="blog_category_id" name="blog_category_id"
                      class="form-select <?= !empty($errors['blog_category_id']) ? 'is-invalid' : '' ?>">
                <option value="">-- Select Category --</option>
                <?php foreach ($categories ?? [] as $cat): ?>
                  <option value="<?= $cat->blog_category_id ?>"
                    <?= ($old['blog_category_id'] ?? '') == $cat->blog_category_id ? 'selected' : '' ?>>
                    <?= $this->e($cat->name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (!empty($errors['blog_category_id'])): ?>
                <div class="invalid-feedback"><?= $this->e($errors['blog_category_id']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="tag_ids" class="form-label">Tags</label>
              <select id="tag_ids" name="tag_ids[]" multiple
                      class="form-select <?= !empty($errors['tag_ids']) ? 'is-invalid' : '' ?>">
                <?php foreach ($tags ?? [] as $tag): ?>
                  <option value="<?= $tag->blog_tag_id ?>"
                    <?= in_array($tag->blog_tag_id, $old['tag_ids'] ?? []) ? 'selected' : '' ?>>
                    <?= $this->e($tag->name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (!empty($errors['tag_ids'])): ?>
                <div class="invalid-feedback"><?= $this->e($errors['tag_ids']) ?></div>
              <?php endif; ?>
            </div>

          </div>
        </div>

        <!-- Actions Row -->
        <div class="mt-4 justify-content-between d-sm-none d-flex">
          <a href="/admin/blog/posts" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Cancel
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Save Changes
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- EasyMDE from CDN -->
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const easyMDE = new EasyMDE({
      element: document.getElementById("content"),
      spellChecker: false,
      placeholder: "Write your blog post in Markdown...",
      minHeight: "300px",
      toolbar: [
        "bold", "italic", "heading", "|",
        "quote", "unordered-list", "ordered-list", "|",
        "link", "image", "|",
        "preview", "side-by-side", "fullscreen", "|",
        "guide"
      ]
    });
  });
</script>
