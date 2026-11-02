<?php
session_start();
include("../config/db.php");

$error = "";

/* 🔐 AUTO LOGIN (remember me) */
if(!isset($_SESSION['id']) && isset($_COOKIE['user_id'])){
    $_SESSION['id'] = $_COOKIE['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$_COOKIE['user_id']]);
    $user = $stmt->fetch();
    if($user){
    $_SESSION['role'] = $user['role'];
    }
}

/* LOGIN */
if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // ✅ VERSION SÉCURISÉE
    if($user && password_verify($password, $user['password'])){
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if(isset($_POST['remember'])){
            setcookie("user_id", $user['id'], time() + (86400 * 30), "/");
        }

        if($user['role'] == 'admin'){
    header("Location: ../admin/dashboard.php");
}
elseif($user['role'] == 'chef'){
    header("Location: ../chef/dashboard.php");
}
else{
    header("Location: ../etudiant/dashboard.php");
}
        exit();
    } else {
        $error = "Email ou mot de passe incorrect!";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Resto Univ</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Pacifico&display=swap" rel="stylesheet">
<style>
    :root{
  --primary: #ffc107;
  --primary-light: #ffcf33;
  --dark: #1f1f1f;
  --card: #2b2b2b;
  --input: #3a3a3a;
  --text: #f5f5f5;
  --muted: #cfcfcf;
}

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  scroll-behavior:smooth;
  font-family:'Poppins',sans-serif;
}

body{
  background:linear-gradient(135deg,#2b2b2b,#3a3a3a);
  display:flex;
  justify-content:center;
  align-items:center;
  min-height:100vh;
  padding:20px;
  color:var(--text);
}

/* CONTAINER */
.hero{
  width:100%;
  max-width:100%;
  background:var(--card);
  border-radius:18px;
  overflow:hidden;
  display:flex;
  border:1px solid var(--stroke);
  box-shadow:0 25px 80px rgba(0,0,0,0.45);
  backdrop-filter:blur(18px);
  animation: fadeIn 0.8s ease;
  position:relative;
}
@keyframes fadeIn{ 
  from{ 
  opacity:0; 
  transform:translateY(30px); 
} 
to{ 
  opacity:1; 
  transform:translateY(0); 
} 
}
/* LEFT */
.left-side{
  width:50%;
  position: relative;
  overflow: hidden;
  background:var(--dark);
}

.left-side img{
  width:100%;
  height:100%;
  object-fit:cover;
  filter:brightness(0.8);
}

/* CURVE */
.curve{
  position:absolute;
  top:-40px;
  right:-80px;
  width:180px;
  height:110%;
  background:var(--card);
  border-left:6px solid rgba(255,193,7,0.5);
  border-radius:50%;
  
}
.deco {
    position: absolute;
    opacity: 0.18; 
    z-index: 1; 
    fill: #ffc107; 
    stroke: #ffc107; 
} 
.deco.one { 
    top: 150px; 
    right: 130px; 
    width: 55px; 
}
 .deco.two { 
    top: 290px; 
    left: 35px; 
    width: 65px; 
}
 .deco.three { 
    bottom: 120px;
    right: 90px; 
    width: 60px; 
} .deco.four { 
    bottom: 60px; 
    left: 130px; 
    width: 70px; 
} 
.deco.five { 
    top: 120px; 
    left: 20px; 
    width: 26px; 
} 
.deco.six { 
    bottom: 90px; 
    right: 250px; 
    width: 28px; 
}
/* RIGHT */
.right-side{
  width:50%;
  padding:40px 50px;
  display:flex;
  flex-direction:column;
  justify-content:center;
}

/* NAVBAR */
.navbar{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:50px;
  flex-wrap:wrap;
  gap:15px;
}

.logo{
  margin-bottom:18px;
  letter-spacing:1px;
  color:var(--primary);
}

.logo span{
  display:block;
  font-size:0.85rem;
  color:var(--muted);
}

.nav-links{
  display:flex;
  gap:25px;
  list-style:none;
}

.nav-links a{
  text-decoration:none;
  color:var(--text);
  font-size:0.95rem;
  position:relative;
  transition:0.3s;
}

.nav-links a::after{
  content:"";
  position:absolute;
  left:0;
  bottom:-3px;
  width:0%;
  height:2px;
  background:var(--primary);
  transition:0.3s;
}

.nav-links a:hover{
  color:var(--primary);
}

.nav-links a:hover::after{
  width:100%;
}

/* TITLES */
h1, h2{
  color:var(--primary);
  font-size: 28px;
  margin-bottom:6px;
}

/* TEXT */
p{
  color:var(--muted);
  line-height:1.6;
  margin-bottom:20px;
}

/* INPUTS */
input, select{
  width:100%;
  padding:13px;
  margin-bottom: 14px;
  border:none;
  border-radius:10px;
  background:var(--input);
  color:var(--text);
  font-size:0.95rem;
  transition:0.25s;
}

input:focus, select:focus{
  outline:none;
  box-shadow:0 0 0 2px rgba(255,193,7,0.5);
}

/* LABEL */
label{
  font-size:13px;
  color:var(--muted);
}
form label{
  margin-top: 8px;
  display:block;
}
/* BUTTON */
button, .btn{
  display:inline-block;
  width:100%;
  padding:13px;
  background:var(--primary);
  color:#222;
  border:none;
  border-radius:8px;
  font-weight:600;
  cursor:pointer;
  transition:0.3s;
  text-align:center;
  text-decoration:none;
}

button:hover, .btn:hover{
  background:var(--primary-light);
  transform:translateY(-2px);
}

/* OPTIONS */
.options{
  display:flex;
  justify-content:space-between;
  align-items:center;
  font-size:13px;
  margin:10px 0 20px;
}

.remember{
  display:flex;
  align-items:center;
  gap:8px;
  cursor: pointer;
}

.remember input{
  width:15px;
  height:15px;
  accent-color: var(--card);
  cursor: pointer;
}
.options a{
  color:var(--primary);
  text-decoration:none;
}
 .forgot{ 
  white-space:nowrap; 
  text-decoration:none; 
} 
.options a:hover{
  text-decoration:underline;
}
.forgot:hover{ 
  text-decoration:underline; } 
.btn{
width:100%;
padding:12px;
background:#ffc107;
border:none;
border-radius:8px;
font-weight:bold;
cursor:pointer;
transition:0.3s;
}  
 .btn:hover{
background:#e0a800;
}
.btn:active{
  transform: translate(2px, 2px);
  box-shadow: 0px 0px 2 rgba(255,193,7,0.5);
}
.footer-text{
display:inline-flex;
text-align:center;
justify-content:center;
margin-top:18px;
font-size:13px;
}
.footer-test a{
color:#ffc107;
text-decoration:none;
font-weight:500;
}
.footer-test a:hover{
  text-decoration: underline;
}
/* LINKS */
.links{
  margin-top:15px;
  font-size:0.85rem;
}

.links a{
  color:var(--primary);
  text-decoration:none;
}

.links a:hover{
  text-decoration:underline;
}

/* ERROR */
.error{
  color:#ff4d4d;
  margin-bottom:10px;
}

/* SUCCESS */
.success{
  color:#4caf50;
  margin-bottom:10px;
}

/* RESPONSIVE */
@media(max-width:900px){
  .hero{
    flex-direction:column;
  }

  .left-side,
  .right-side{
    width:100%;
  }

  .left-side{
    height:250px;
  }
   .options {
        flex-direction: column;
        align-items: flex-start;
      }
}
/* PAGE TRANSITION */
body {
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.4s ease;
}

body.fade-in {
  opacity: 1;
  transform: translateY(0);
}

body.fade-out {
  opacity: 0;
  transform: translateY(-20px);
}
</style>
</head>

<body>

<div class="hero">

    <!-- IMAGE -->
    <div class="left-side">
        <img src="../assets/images/login.jpeg">
        <div class="curve"></div>
    </div>

    <div class="right-side">
      <nav class="navbar">
        <div class="logo">
          RestoUniv
        </div>
        <ul class="nav-links">
          <li><a href="/resto_project/index.php">Accueil</a></li>
          <li><a href="../contact.php">Contact</a></li>
        </ul>
      </nav>
    <!-- FORM -->
        <h1>Connexion </h1>
        <p class="subtitle">Connecte-toi pour accéder à ton espace</p>

        <?php if($error!="") echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="exemple@gmail.com" required>

            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="********" required>

            <div class="options">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Se souvenir de moi</span>
                </label>
                <a href="forgot.php" class="forgot">Mot de passe oublié ?</a>
            </div>

            <button class="btn" name="login">Se connecter</button>
        </form>

        <div class="links">
            <p class="footer-text">Pas encore de compte ?<a href="register.php">Créer un compte</a></p>
        </div>
        <svg class="deco one" viewBox="0 0 64 64">
        <path d="M32 58C20 50 14 39 14 27C14 17 21 10 31 10C37 10 41 13 44 18C47 13 51 10 57 10C67 10 74 17 74 27C74 39 68 50 56 58"
              transform="translate(-12 -4)"
              fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

      <!-- Icône blé -->
      <svg class="deco two" viewBox="0 0 64 64">
        <path d="M32 56V14M32 22c-6 0-10-4-10-10M32 28c-6 0-10-4-10-10M32 34c-6 0-10-4-10-10M32 22c6 0 10-4 10-10M32 28c6 0 10-4 10-10M32 34c6 0 10-4 10-10"
              fill="none" stroke-width="2.5" stroke-linecap="round"/>
      </svg>

      <!-- Icône cloche repas -->
      <svg class="deco three" viewBox="0 0 64 64">
        <path d="M10 42h44M18 42a14 14 0 0 1 28 0M32 20v-4"
              fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>

      <!-- Icône assiette -->
      <svg class="deco four" viewBox="0 0 64 64">
        <circle cx="32" cy="32" r="18" fill="none" stroke-width="2.5"/>
        <circle cx="32" cy="32" r="7" fill="none" stroke-width="2.5"/>
      </svg>

      <!-- Petit cercle -->
      <svg class="deco five" viewBox="0 0 64 64">
        <circle cx="32" cy="32" r="12" fill="none" stroke-width="2.5"/>
        <circle cx="32" cy="32" r="3" stroke="none"/>
      </svg>

      <!-- Petit cercle -->
      <svg class="deco six" viewBox="0 0 64 64">
        <circle cx="32" cy="32" r="12" fill="none" stroke-width="2.5"/>
        <circle cx="32" cy="32" r="3" stroke="none"/>
      </svg>
    </div>
</div>
</body>
<script id="pageTransition">
document.addEventListener("DOMContentLoaded", () => {
  document.body.classList.add("fade-in");

  document.querySelectorAll("a").forEach(link => {
    link.addEventListener("click", function(e) {

      // ignore external / empty links
      if (this.href && !this.href.includes("#")) {
        e.preventDefault();

        document.body.classList.remove("fade-in");
        document.body.classList.add("fade-out");

        setTimeout(() => {
          window.location = this.href;
        }, 300);
      }
    });
  });
});
</script>
</html>