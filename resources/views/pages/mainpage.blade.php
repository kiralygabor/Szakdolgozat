<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Dropdown</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #f4f6fb;
    }

    .profile-navbar {
      width: 100%;
      display: flex;
      justify-content: flex-end;
      padding: 15px 5%;
      background: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      position: relative;
    }

    .user-pic {
      width: 40px;
      border-radius: 50%;
      cursor: pointer;
    }

    .sub-menu-wrap {
      position: absolute;
      top: 60px;
      right: 5%;
      width: 280px;
      max-height: 0px;
      overflow: hidden;
      transition: max-height 0.3s ease;
    }

    .sub-menu-wrap.open-menu {
      max-height: 500px;
    }

    .sub-menu {
      background: #fff;
      border-radius: 12px;
      padding: 15px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    .user-info {
  margin-bottom: 15px;
  padding: 10px 12px;
  border-radius: 8px;
  transition: background 0.2s ease, color 0.2s ease;
  cursor: pointer;
}

.user-info:hover {
  background: #007bff; /* blue background */
}

.user-info:hover h3 a,
.user-info:hover p {
  color: #fff; /* white text */
}

    .user-info h3 a {
      font-size: 15px;
      font-weight: 600;
      color: #1a1a1a;
      display: block;
      text-decoration: none;
    }

    .user-info p {
      font-size: 13px;
      color: #888;
      margin-top: 2px;
    }

    .sub-menu hr {
      border: 0;
      height: 1px;
      background: #eee;
      margin: 10px 0;
    }

    .sub-menu-link {
      display: block;
      text-decoration: none;
      color: #333;
      font-size: 14px;
      padding: 10px 0;
      transition: color 0.2s ease;
    }

    .sub-menu-link:hover {
      color: #007bff;
    }

  </style>
</head>
<body>
  <nav class="profile-navbar">
    <img src="img/user.png" class="user-pic" onclick="toggleMenu()">

    <div class="sub-menu-wrap" id="subMenu">
      <div class="sub-menu">
        <div class="user-info">
         <h3><a href="{{ route('profile') }}">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a></h3>
          <p>Public Profile</p>
        </div>
        <hr>
        <a href="#" class="sub-menu-link">My Tasker Dashboard</a>
        <a href="#" class="sub-menu-link">Payment history</a>
        <a href="#" class="sub-menu-link">Payment methods</a>
        <a href="#" class="sub-menu-link">Settings</a>
        <a href="#" class="sub-menu-link">Discover</a>
        <a href="#" class="sub-menu-link">Help topics</a>
        <hr>
        <a href="{{ route('logout') }}" class="sub-menu-link">Logout</a>
      </div>
    </div>
  </nav>

  <script>
    let subMenu = document.getElementById("subMenu");
    function toggleMenu(){
      subMenu.classList.toggle("open-menu");
    }
  </script>
</body>
</html>
