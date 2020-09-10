<template>
  <header class="site-header">
    <div class="container-fluid">
      <div class="logo-area">
        <a href="/">
          <img src="img/main-logo.png" alt />
        </a>
      </div>
      <span class="menu-toggle" onclick="openNav()">&#9776;</span>
    </div>
    <div id="mySidenav" class="sidenav">
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
      <a href="/about">About</a>
      <a href="/services">Services</a>
      <a href="/faq">FAQ</a>
      <a href="/contact">Contact</a>
    </div>
  </header>
</template>

<script>
export default {};
</script>
