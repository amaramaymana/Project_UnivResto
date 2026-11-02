<?php
session_start();
include("../config/db.php");

$error = "";
$success = "";

if(isset($_POST['register'])){
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $role = 'etudiant';

    /* 🔒 Validation */
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Email invalide!";
    }
    elseif(strlen($password) < 6){
        $error = "Mot de passe doit contenir au moins 6 caractères!";
    }
    elseif($password !== $confirm){
        $error = "Les mots de passe ne correspondent pas!";
    }
    else{
        /* 🔍 Vérifier email */
        $check = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $check->execute([$email]);

        if($check->rowCount() > 0){
            $error = "Email déjà utilisé!";
        } else {

            /* 🔐 HASH SÉCURISÉ */
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users(nom,email,password,role) VALUES(?,?,?,?)");
            $stmt->execute([$nom,$email,$hashed,$role]);

            /* ✅ success */
            $success = "Compte créé avec succès!";
            
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Pacifico&display=swap" rel="stylesheet">
<style>
  :root{
  --primary:#ffc107;
  --primary-light:#ffcf33;
  --dark:#1f1f1f;
  --card:#2b2b2b;
  --input:#3a3a3a;
  --text:#f5f5f5;
  --muted:#cfcfcf;
}

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  scroll-behavior:smooth;
}

body{
  font-family:'Inter', sans-serif;
  background:linear-gradient(135deg,#2b2b2b,#3a3a3a);
  display:flex;
  justify-content:center;
  align-items:center;
  min-height:100vh;
  padding:25px;
  color:var(--text);
}

/* CONTAINER */
.hero{
  width:95%;
  max-width:1300px;
  background:var(--card);
  border-radius:14px;
  overflow:hidden;
  display:flex;
  box-shadow:0 25px 60px rgba(0,0,0,0.45);
  animation: fadeIn 0.8s ease;
  position:relative;
}
@keyframes fadeIn{
   from{ 
    opacity:0; 
    transform:translateY(30px); 
  } to{ 
    opacity:1; 
    transform:translateY(0); 
  } 
}
/* LEFT */
.left-side{
  width:50%;
  position:relative;
  overflow: hidden;
  background:var(--dark);
}

.left-side img{
  width:100%;
  height:100%;
  object-fit:cover;
  filter:brightness(0.85);
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
  z-index: 2;
}
.right-side{
  width: 50%;
  position: relative;
  padding: 35px 60px 40px 70px; 
  z-index: 3;
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
  font-family:'Inter', sans-serif;
  color:var(--primary);
  margin-bottom:15px;
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
  padding:12px 14px;
  margin:8px 0 15px;
  border:none;
  border-radius:8px;
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
  font-size:0.8rem;
  color:var(--muted);
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
  gap: 12px;
  flex-wrap: wrap;
  color:var(--muted);
  font-size:0.8rem;
  margin-top: -4px;
}

.remember{
  display:flex;
  align-items:center;
  gap:8px;
  cursor:pointer;
}

.remember input{
  width:16px;
  height:16px;
  border:2px solid var(--primary);
  border-radius:4px;
  background:var(--card);
  box-shadow: none;
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

    /* FOOTER STYLES */
    .footer-info {
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid rgba(255,193,7,0.2);
      text-align: center;
    }
    .copyright {
      font-size: 0.8rem;
      color: var(--muted);
      opacity: 0.7;
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

  <div class="left-side">
    <img src="../assets/images/apropos.jpeg">
    <div class="curve"></div>
  </div>

  <div class="right-side">
    <nav class="navbar">
      <div class="logo">RestoUniv</div>
      <ul class="nav-links">
           <li><a href="../index.php">Acceuil</a></li>
          <li><a href="../apropos.php">À propos</a></li>
          <li><a href="../contact.php">Contact</a></li>
      </ul>
      </nav>
    <h2>Créer un compte</h2>

    <?php if($error!="") echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
      <label>Nom</label>
      <input type="text" name="nom" placeholder="Nom" required>
      <label>Email</label>
      <input type="email" name="email" placeholder="Email" required>
      <label>Mot de passe</label>
      <input type="password" name="password" placeholder="Password" required>
      <label>Confirmer</label>
      <input type="password" name="confirm_password" placeholder="Confirmer Password" required>

      <select name="role" disabled>
  <option>Etudiant</option>
</select>
      <div class="options">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>J'accepter tout les conditions </span>
                </label>
      </div>
      <button name="register">Créer</button>
    </form>
    <div class="links">
    <p>Déjà un compte ?<a href="login.php">Se connecter</a></p>
  </div>
   <!-- Contact Information Section -->
      <div class="footer-info">
        <div class="copyright">
          <p>© 2026 SmartMeal | Projet Universitaire</p>
        </div>
      </div>
<!-- Icône feuille -->
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