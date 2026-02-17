<?php

if (!isset($currentCategory)) {
    $currentCategory = isset($_GET['category']) ? $_GET['category'] : '';
}
?>
<aside class="sidebar">
  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
  <h3>Categories</h3>
  <ul>
    <li class="<?= ($currentCategory === 'all' || $currentCategory === '') ? 'active' : '' ?>">
      <a href="index.php?category=all">All Products</a>
    </li>
    <li class="<?= $currentCategory === 'Processor' ? 'active' : '' ?>">
      <a href="index.php?category=Processor">Processor</a>
    </li>
    <li class="<?= $currentCategory === 'Mouse' ? 'active' : '' ?>">
      <a href="index.php?category=Mouse">Mouse</a>
    </li>
    <li class="<?= $currentCategory === 'Keyboard' ? 'active' : '' ?>">
      <a href="index.php?category=Keyboard">Keyboard</a>
    </li>
    <li class="<?= $currentCategory === 'Monitor' ? 'active' : '' ?>">
      <a href="index.php?category=Monitor">Monitor</a>
    </li>
    <li class="<?= $currentCategory === 'CPU Case' ? 'active' : '' ?>">
      <a href="index.php?category=CPU Case">CPU Case</a>
    </li>
    <li class="<?= $currentCategory === 'Graphic Cards' ? 'active' : '' ?>">
      <a href="index.php?category=Graphic Cards">Graphic Cards</a>
    </li>
    <li class="<?= $currentCategory === 'RAM' ? 'active' : '' ?>">
      <a href="index.php?category=RAM">RAM</a>
    </li>
    <li class="<?= $currentCategory === 'Storage' ? 'active' : '' ?>">
      <a href="index.php?category=Storage">Storage</a>
    </li>
    <li class="<?= $currentCategory === 'PSU' ? 'active' : '' ?>">
      <a href="index.php?category=PSU">PSU</a>
    </li>
    <li class="<?= $currentCategory === 'headset' ? 'active' : '' ?>">
      <a href="index.php?category=headset">Headset</a>
    </li>

    <li class="<?= $currentCategory === 'controller' ? 'active' : '' ?>">
      <a href="index.php?category=controller">Controller</a>
    </li>
  </ul>
</aside>