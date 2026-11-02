<?php
session_start();
include("../config/db.php");

/* PROTECTION */
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'chef'){
    header("Location: ../auth/login.php");
    exit();
}

/* GET MEALS + COUNT CONFIRMATIONS */
$sql = "
SELECT m.*, COUNT(c.id) AS total
FROM meals m
LEFT JOIN confirmations c ON m.id = c.meal_id
GROUP BY m.id
ORDER BY m.date DESC
";

$stmt = $pdo->query($sql);
$meals = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Chef</title>

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
--stroke:#444;
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
  width:100%;
  max-width:1400px;
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
  width:40%;
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
    fill: none;
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
  width:60%;
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
  margin-bottom:0;
  letter-spacing:1px;
  color:var(--primary);
  font-weight:600;
  font-size:1.2rem;
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
  background: var(--primary);
  color: #1f1f1f;
  padding:10px 20px;   
  font-size:14px;
  border-radius:8px;
  font-weight:500;
  margin-bottom:20px;
  display:inline-block;
  width:fit-content;
}

.btn-add:hover{
  background: var(--primary-light);
  transform:translateY(-2px);
}

.btn-modifier{
  background: #b8860b;
  color: white;
}

.btn-supprimer{
  background: #8B0000;
  color:white;
}

.btn-modifier:hover{
  background: #cd950c;
}

.btn-supprimer:hover{
  background: #a00000;
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
.table-container{
  overflow-x:auto;
  margin-top:10px;
}

table{
  width:100%;
  border-collapse:collapse;
  margin-top:10px;
  font-size:14px;
}

th, td{
  padding:12px;
  border:1px solid #444;
  text-align:center;
}

th{
  background:#ffc107;
  color:#222;
  font-weight:600;
}

td .btn{
  margin:2px;
  padding:6px 10px;
  font-size:12px;
}

tr:hover{
  background:#3a3a3a;
}

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
  
  .right-side{
    padding:30px 20px;
  }
  
  .navbar{
    flex-direction:column;
    gap:15px;
  }
  
  table{
    font-size:12px;
  }
  
  th, td{
    padding:8px;
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

<script>
// Page transition
document.addEventListener('DOMContentLoaded', function() {
  document.body.classList.add('fade-in');
  
  // Handle all links with transition
  document.querySelectorAll('a').forEach(link => {
    if(link.href && !link.href.includes('javascript:') && link.getAttribute('onclick') !== 'return confirm') {
      link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if(href && !href.startsWith('#') && !this.hasAttribute('target')) {
          e.preventDefault();
          document.body.classList.remove('fade-in');
          document.body.classList.add('fade-out');
          setTimeout(() => {
            window.location.href = href;
          }, 400);
        }
      });
    }
  });
});
</script>
</head>

<body>
<div class="hero">

  <!-- IMAGE -->
  <div class="left-side">
    <img src="../assets/images/chef.jpeg" alt="Chef">
    <div class="curve"></div>
  </div>

  <div class="right-side">
    <div class="navbar">
      <div class="logo">RestoUniv</div>
      <ul class="nav-links">
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="../auth/logout.php">Déconnexion</a></li>
      </ul>
    </div>
    <h2 style="color:#ffc107;margin-bottom:20px;">Bienvenue Chef</h2>

    <a href="add_meal.php" class="btn btn-add">Ajouter un repas</a>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Entrée</th>
            <th>Plat</th>
            <th>Dessert</th>
            <th>Date</th>
            <th>Type</th>
            <th>Confirmations</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if(count($meals) > 0): ?>
            <?php foreach($meals as $m): ?>
            <tr>
              <td><?= htmlspecialchars($m['id']) ?></td>
              <td><?= htmlspecialchars($m['entree']) ?></td>
              <td><?= htmlspecialchars($m['plat']) ?></td>
              <td><?= htmlspecialchars($m['dessert']) ?></td>
              <td><?= htmlspecialchars($m['date']) ?></td>
              <td><?= htmlspecialchars($m['type']) ?></td>
              <td style="color:#ffc107; font-weight:bold;">
                <?= htmlspecialchars($m['total']) ?>
              </td>
              <td>
                <a href="edit_meal.php?id=<?= $m['id'] ?>" class="btn btn-modifier"> Modifier</a>
                <a href="delete_meal.php?id=<?= $m['id'] ?>" 
                   class="btn btn-supprimer"
                   onclick="return confirm('Supprimer ce repas ?')">
                   Supprimer
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" style="text-align:center; padding:40px;">
                Aucun repas trouvé. Cliquez sur "Ajouter un repas" pour commencer.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
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
</html>