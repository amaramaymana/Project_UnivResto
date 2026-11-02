<?php
session_start();
include("../config/db.php");
/* PROTECTION ADMIN */
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

$error = "";
$success = "";

if(isset($_POST['add_chef'])){

    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    /* VALIDATION */
    if(empty($nom) || empty($email) || empty($password)){
        $error = " Tous les champs sont obligatoires!";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Email invalide!";
    }
    elseif(strlen($password) < 6){
        $error = " Mot de passe minimum 6 caractères!";
    }
    else{

        /* CHECK EMAIL EXIST */
        $check = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $check->execute([$email]);

        if($check->rowCount() > 0){
            $error = "Email déjà utilisé!";
        } 
        else{

            /*  HASH PASSWORD */
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            /*  INSERT CHEF */
            $stmt = $pdo->prepare("
                INSERT INTO users(nom,email,password,role) 
                VALUES(?,?,?,?)
            ");
            $stmt->execute([$nom,$email,$hashed,'chef']);
            $success = " Chef ajouté avec succès!";
            $_POST = [];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter chef</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
  font-family:'Poppins', sans-serif;
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
  border-radius:14px;
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
/* RIGHT */
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

/* NAV */
.navbar{
  display:flex;
  justify-content:space-between;
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
  gap:20px;
  list-style:none;
}

.nav-links a{
  color:white;
  text-decoration:none;
}.logo span{
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

/* FORM */
input{
  width:100%;
  padding:12px;
  margin:10px 0;
  border:none;
  border-radius:8px;
  background:var(--input);
  color:white;
}

button{
  width:100%;
  padding:12px;
  background:var(--primary);
  border:none;
  border-radius:6px;
  font-weight:bold;
  cursor:pointer;
}

.error{color:#ff4d4d;}
.success{color:#4caf50;}

/* TOAST */
.toast{
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #ffc107;
  color: #222;
  padding: 12px 18px;
  border-radius: 8px;
  font-weight: 500;
  box-shadow: 0 10px 25px rgba(0,0,0,0.3);
  opacity: 0;
  transform: translateY(20px);
  transition: 0.3s;
}

.toast.show{
  opacity:1;
  transform:translateY(0);
}
/* BACK LINK */
a{
  display:block;
  margin-top:15px;
  text-align:center;
  color:#ffc107;
  text-decoration:none;
}
/* RESPONSIVE */
@media(max-width:900px){
  .hero{flex-direction:column;}
  .left-side,.right-side{width:100%;}
  .left-side{height:200px;}
}
</style>
</head>

<body>

<div class="hero">

<div class="left-side">
<img src="../assets/images/add_chef.jpeg">
<div class="curve"></div>
</div>

<div class="right-side">

<nav class="navbar">
<div class="logo">RestoUniv</div>
<ul class="nav-links">
<li><a href="../auth/logout.php">Déconnexion</a></li>
<li><a href="../apropos.php">À propos</a></li>
</ul>
</nav>

<h2>Ajouter Chef</h2>

<?php if($error): ?>
<p class="error"><?= $error ?></p>
<?php endif; ?>

<?php if($success): ?>
<p class="success"><?= $success ?></p>
<?php endif; ?>

<form method="POST">
<input type="text" name="nom" placeholder="Nom du chef" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Mot de passe" required>

<button name="add_chef">Ajouter Chef</button>
</form>

<br>
<a href="dashboard.php">⬅ Retour </a>
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

<script>
let lastCheck = new Date().toISOString();

setInterval(() => {
fetch("check_notifications.php?last=" + lastCheck)
.then(res => res.json())
.then(data => {

if(data && data.length > 0){
lastCheck = new Date().toISOString();

data.forEach(() => {
showToast("🍽 Nouveau repas ajouté !");
});
}

})
.catch(err => console.log(err));

}, 5000);

function showToast(msg){
let toast = document.createElement("div");
toast.className = "toast";
toast.innerText = msg;

document.body.appendChild(toast);

setTimeout(()=> toast.classList.add("show"),100);

setTimeout(()=>{
toast.classList.remove("show");
setTimeout(()=> toast.remove(),300);
},3000);
}
</script>

</body>
</html>