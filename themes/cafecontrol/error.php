<?php $this->layout("theme"); ?>

<!--404-->
<article class="not_found">
    <div class="container content">
        <header class="not_found_header">
            <p class="error">404</p>
            <h1><?= $error->title; ?></h1>
            <p><?= $error->message; ?></p>
            
            <?php if($error->link): ?>
            
            <a class="not_found_btn gradient gradient-green gradient-hover transition radius" title="<?= $error->linkTitle; ?>" href="<?= $error->link; ?>"><?= $error->linkTitle; ?></a>
            
            <?php endif; ?>
        </header>
    </div>
</article>
