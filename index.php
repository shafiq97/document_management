<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">


  <link rel="apple-touch-icon" type="image/png"
    href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />

  <meta name="apple-mobile-web-app-title" content="CodePen">

  <link rel="shortcut icon" type="image/x-icon"
    href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />

  <link rel="mask-icon" type="image/x-icon"
    href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg"
    color="#111" />



  <script src="https://cpwebassets.codepen.io/assets/packs/js/authorizeDataCapture-7ffc51c99fc12a2f2825.js"></script>

  <title>CodePen - Website Homepage</title>
  <link rel="canonical" href="https://codepen.io/Creepercraft206/pen/OJBNqNg" />




  <style>
    body {
      margin: 0;
      background-image: linear-gradient(-30deg,
          #3f51b5 0%,
          #2196f3 50%,
          #87ceeb 100%);
      overflow-x: hidden;
      color: white;
      font-family: sans-serif;
      height: 200vh;
    }

    header {
      position: fixed;
      z-index: 500;
    }

    li {
      list-style: none;
      display: inline;
      font-size: 2vw;
      margin: 30px;
    }

    a {
      color: white;
      text-decoration: none;
      position: relative;
    }

    a::after {
      content: "";
      transition: 500ms;
      display: block;
      position: absolute;
      width: inherit;
      height: 0.5vw;
      top: 4vw;
      right: 0;
      bottom: 0;
      left: 0;
      border-radius: 1rem 0;
      background-color: white;
    }

    a:hover::after {
      top: 3vw;
    }

    h1 {
      position: absolute;
      font-size: 6vw;
      z-index: 200;
      top: 35vh;
      left: 40vw;
      font-family: 'Montserrat', sans-serif;
    }

    h1::after {
      content: "";
      display: block;
      position: absolute;
      background-color: #312ecb;
      border-radius: 1rem 0;
      width: 10vw;
      height: 1.5vw;
      top: 5vw;
      right: 0;
      bottom: 0;
      left: auto;
      z-index: -1;
    }

    img {
      position: absolute;
      width: 15vw;
      height: 15vw;
      top: 35vh;
      left: 25vw;
      z-index: 150;
    }

    .blob {
      position: absolute;
      background-color: rgba(156, 195, 230, 0.6);
      background-image: linear-gradient(45deg,
          #3f51b5 0%,
          #2196f3 50%,
          #87ceeb 100%);
    }

    #blob1 {
      right: -10vw;
      top: 15vw;
      width: 50vw;
      height: 30vw;
      rotate: 30deg;
      border-radius: 90% 50%;
      z-index: 150;
    }

    #blob2 {
      left: -10vw;
      top: 35vw;
      width: 70vw;
      height: 30vw;
      border-radius: 30% 70%;
    }

    #blob3 {
      left: -5vw;
      top: 5vw;
      width: 40vw;
      height: 20vw;
      border-radius: 60% 50%;
      rotate: -10deg;
    }

    #line {
      position: absolute;
      top: -13vw;
      left: -10vw;
      width: 120vw;
      height: auto;
      z-index: 120;
    }
  </style>

  <script>
    window.console = window.console || function (t) { };
  </script>



</head>

<body translate="no">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <header>
    <nav>
      <ul>
        <li><a href="login.php">Login</a></li>
      </ul>
    </nav>
  </header>
  <h1>Document Management</h1>
  <img src="https://img.logoipsum.com/245.svg" alt="Logo">
  <div class="blob" id="blob1"></div>
  <div class="blob" id="blob2"></div>
  <div class="blob" id="blob3"></div>
  <svg id="line" viewBox="0 0 500 500" width="500" height="500">
    <path style="stroke-width: 3px; stroke-linecap: round; stroke: rgb(179, 192, 239); paint-order: stroke; fill: none;"
      d="M 410.107 119.627 C 610.559 91.754 447.468 205.23 267.236 230.292 C 98.654 236.087 197.1 61.629 103.197 159.266 C 142.803 121.311 -69.077 302.058 126.896 342.895">
    </path>
  </svg>
</body>

</html>