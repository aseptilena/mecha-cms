<?php echo $messages; ?>
<?php if($files): ?>
<ul>
  <?php foreach($files as $file): ?>
  <li><?php echo ASSET . DS . File::path(Text::parse($file, '->decoded_url')); ?></li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
<form class="form-kill form-asset" id="form-kill" action="<?php echo $config->url_current . $config->url_query; ?>" method="post">
  <?php echo Jot::button('action', $speak->yes); ?> <?php echo Jot::btn('reject', $speak->no, $config->manager->slug . '/asset'); ?>
  <?php echo Form::hidden('token', $token); ?>
</form>