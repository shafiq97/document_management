<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">File Upload</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
    aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="documents.php">Documents</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="upload_docs.php">Upload Document</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="about.php">About</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          Profile
        </a>
        <div class="dropdown-menu" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="profile.php">My Profile</a>
          <a class="dropdown-item" href="following.php">Following</a>
          <a class="dropdown-item" href="followers.php">Followers</a>
          <a class="dropdown-item" href="favourite-list.php">Favourite</a>
          <a class="dropdown-item" href="collections.php">Collection</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="contact.php">Contact</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="confirmLogout()">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<script>
  function confirmLogout() {
    if (confirm("Are you sure you want to logout?")) {
      window.location.href = "../logout.php";
    }
  }
</script>