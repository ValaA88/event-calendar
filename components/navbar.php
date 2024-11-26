<?php


if (!isset($_SESSION["admin"]) && !isset($_SESSION["user"])) {
  $links = "<a class='navbar-brand' href='/login.php'>Login</a>";
  $createEvent = "<a class='navbar-brand, btn btn-outline-success' href='/login.php'>Create Event</a>";
} else {
  $links = "<a class='navbar-brand' href='/logout.php?logout'>Logout</a>";
  $createEvent = "<a class='navbar-brand, btn btn-outline-success' href='/create.php'>Create Event</a>";
}


?>
<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <div class="d-flex gap-4">

      <img src="/images/logo.jpg" alt="..." width="50" height="50">

      <a class="navbar-brand" href="/">
        Home</a>
    </div>

    <div class="d-flex gap-4">

      <?= $createEvent ?>
      <a class="navbar-brand" href="#">About us</a>
      <a class="navbar-brand" href="#">FAQ</a>
    </div>

    <?= $links ?>


  </div>
</nav>