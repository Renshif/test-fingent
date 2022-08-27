<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Page navigation example">
  <ul class="pagination">
    <?php if ($pager->hasPrevious()) : ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Previous">
          <span>&laquo;</span>
        </a>
      </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
      <li class="page-item"><a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a></li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Next">
          <span>&raquo;</span>
        </a>
      </li>
    <?php endif ?>
  </ul>
</nav>
