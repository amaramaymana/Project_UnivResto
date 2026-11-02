<?php
session_start();
include("../config/db.php");

/* 🔐 PROTECTION ADMIN */
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}
$chefs = $pdo->query("
SELECT * FROM users 
WHERE role='chef'
ORDER BY nom
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestion Chefs</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

.hero{
  width:980%;
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


/* NAVBAR */

.navbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
}

.logo{
  margin-bottom:18px;
  letter-spacing:1px;
  color:var(--primary);
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
.btn-add{
background: #ffcf33;
  color:white;
  padding:12px;  
  font-size:12px;
  border: radius 0.6em;
}

.btn-modifier{
 background: #b37c17;
  color: white;
}

.btn-supprimer{
background: #b37c17;
  color:white;
}
.reset{
background: #b37c17;
  color:white;
}
.btn-modifier:hover{
background: #b37c17;
}

.btn-supprimer:hover{
background: #b37c17;
;
}
.reset:hover{
background: #b37c17;
}
.btn{
  padding:8px 12px;
  border:none;
  border-radius:8px;
  text-decoration:none;
  font-size:13px;
  display:inline-block;
  transition:0.2s;
}

.btn:hover{
transform:translateY(-2px);
}

/* TABLE */
table{
  width:100%;
  border-collapse:collapse;
  margin-top:20px;
}

th, td{
  padding:12px;
  border:1px solid #444;
  text-align:center;
}

th{
  background:#ffc107;
  color:#222;
}
td .btn{
margin:2px;
padding:6px 10px;
font-size:12px;
}
tr:hover{
  background:#3a3a3a;
}
/* TITLE */
h2{
color: #ffc107;
margin-bottom:20px;
}
.back{
margin-top:15px;
display:inline-block;
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

<!-- LEFT IMAGE -->
<div class="left-side">
<img src="../assets/images/liste_chef.jpeg">
<div class="curve"></div>
</div>

<!-- RIGHT -->
<div class="right-side">

<!-- NAV -->
<div class="navbar">
<div class="logo">RestoUniv</div>

<ul class="nav-links">
<li><a href="../auth/logout.php">Déconnexion</a></li>
<li><a href="../apropos.php">À propos</a></li>
</ul>
</div>
<h2>Gestion des Chefs</h2>

<a href="add_chef.php" class="btn btn-add"> Ajouter</a>
<table>
<tr>
<th>ID</th>
<th>Nom</th>
<th>Email</th>
<th>Actions</th>
</tr>

<?php foreach($chefs as $chef): ?>
<tr>
<td><?= $chef['id'] ?></td>
<td><?= htmlspecialchars($chef['nom']) ?></td>
<td><?= htmlspecialchars($chef['email']) ?></td>
<td>

<a class="btn btn-modifier" href="edit_chef.php?id=<?= $chef['id'] ?>">Modifier</a>

<a class="btn btn-supprimer" 
href="delete_chef.php?id=<?= $chef['id'] ?>" 
onclick="return confirm('Supprimer ce chef ?')">supprimer</a>

</td>
</tr>
<?php endforeach; ?>

</table>
<a class="back" href="dashboard.php">⬅ Retour</a>
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
</body>
</html>